<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Usaha;
use App\Models\Produk;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    /**
     * Ambil ID transaksi yang sudah difilter (tahun, bulan, user, usaha, kategori)
     */
    private function getFilteredTransactionIds(Request $request)
    {
        $query = Transaction::query();

        // Filter waktu & user (kasir)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_transaksi', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_transaksi', $request->bulan);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $filteredTransactionIds = $query->pluck('id');

        // Filter usaha & kategori via detail_transaksi + produk
        if ($request->filled('usaha_id') || $request->filled('kategori_id')) {
            $productQuery = Produk::query();

            if ($request->filled('usaha_id')) {
                $productIdsByUsaha = DB::table('usaha_produk')
                    ->where('usaha_id', $request->usaha_id)
                    ->pluck('produk_id');
                $productQuery->whereIn('id', $productIdsByUsaha);
            }

            if ($request->filled('kategori_id')) {
                $productQuery->where('kategori_produk_id', $request->kategori_id);
            }

            $validProductIds = $productQuery->pluck('id');

            $filteredTransactionIdsFromProducts = DB::table('detail_transaksi')
                ->whereIn('transaksi_id', $filteredTransactionIds)
                ->whereIn('produk_id', $validProductIds)
                ->distinct('transaksi_id')
                ->pluck('transaksi_id');

            return $filteredTransactionIdsFromProducts;
        }

        return $filteredTransactionIds;
    }

    public function index(Request $request)
    {
        // 1. Ambil semua transaksi yang lolos filter
        $filteredTransactionIds = $this->getFilteredTransactionIds($request);

        if ($filteredTransactionIds->isEmpty()) {
            // kalau kosong, biar aman kirim array kosong ke view
            $totalTransaksi = 0;
            $totalPendapatan = 0;
            $topProduk = '-';
            $userAktif = '-';
            $pendapatanChart = ['labels' => [], 'data' => []];
            $produkTerlarisChart = ['labels' => [], 'data' => []];
            $produkFavoriteChart = ['labels' => [], 'data' => []];
            $produkViewChart = ['labels' => [], 'data' => []];
            $transaksiUserChart = ['labels' => [], 'data' => []];
            $kategoriChart = ['labels' => [], 'data' => []];
        } else {
            $finalQuery = Transaction::whereIn('id', $filteredTransactionIds);

            // Query base detail_transaksi
            $detailTransaksiQuery = DB::table('detail_transaksi as dt')
                ->whereIn('dt.transaksi_id', $filteredTransactionIds)
                ->join('produk as p', 'p.id', '=', 'dt.produk_id');

            // --- SUMMARY METRICS ---
            $totalTransaksi = $finalQuery->count();
            $totalPendapatan = $finalQuery->sum('total');

            // Produk Terlaris (nama untuk metric)
            $topProdukData = (clone $detailTransaksiQuery)
                ->select('p.nama_produk', DB::raw('SUM(dt.jumlah) as total_terjual'))
                ->groupBy('p.id', 'p.nama_produk')
                ->orderByDesc('total_terjual')
                ->first();
            $topProduk = $topProdukData->nama_produk ?? '-';

            // User aktif (kasir dengan transaksi terbanyak)
            $userAktifData = User::whereHas('transaksi', function ($q) use ($filteredTransactionIds) {
                $q->whereIn('id', $filteredTransactionIds);
            })
                ->withCount([
                    'transaksi' => function ($q) use ($filteredTransactionIds) {
                        $q->whereIn('id', $filteredTransactionIds);
                    }
                ])
                ->orderByDesc('transaksi_count')
                ->first();
            $userAktif = $userAktifData->username ?? '-';

            // --- CHART: Pendapatan Top 3 Usaha ---
            $pendapatanRaw = DB::table('detail_transaksi as dt')
                ->whereIn('dt.transaksi_id', $filteredTransactionIds)
                ->join('produk as p', 'p.id', '=', 'dt.produk_id')
                ->join('usaha_produk as up', 'up.produk_id', '=', 'p.id')
                ->join('usaha as u', 'u.id', '=', 'up.usaha_id')
                ->select('u.nama_usaha', DB::raw('SUM(dt.subtotal) as total'))
                ->groupBy('u.id', 'u.nama_usaha')
                ->orderByDesc('total')
                ->limit(3)
                ->get();

            $pendapatanChart = [
                'labels' => $pendapatanRaw->pluck('nama_usaha'),
                'data' => $pendapatanRaw->pluck('total'),
            ];

            // --- CHART: Top 3 Produk Terlaris (Penjualan) ---
            $terlarisRaw = (clone $detailTransaksiQuery)
                ->select('p.nama_produk', DB::raw('SUM(dt.jumlah) as total_terjual'))
                ->groupBy('p.id', 'p.nama_produk')
                ->orderByDesc('total_terjual')
                ->limit(3)
                ->get();

            $produkTerlarisChart = [
                'labels' => $terlarisRaw->pluck('nama_produk'),
                'data' => $terlarisRaw->pluck('total_terjual'),
            ];

            // --- FILTER PRODUK untuk Favorite & Views (usaha + kategori) ---
            $productFilterIds = null;
            if ($request->filled('usaha_id') || $request->filled('kategori_id')) {
                $productQuery = Produk::query();

                if ($request->filled('usaha_id')) {
                    $productIdsByUsaha = DB::table('usaha_produk')
                        ->where('usaha_id', $request->usaha_id)
                        ->pluck('produk_id');
                    $productQuery->whereIn('id', $productIdsByUsaha);
                }

                if ($request->filled('kategori_id')) {
                    $productQuery->where('kategori_produk_id', $request->kategori_id);
                }

                $productFilterIds = $productQuery->pluck('id');
            }

            // --- CHART: Top 3 Produk Favorite (Like) ---
            $favoriteQuery = DB::table('produk_likes as pl')
                ->join('produk as p', 'p.id', '=', 'pl.produk_id');

            // filter waktu berdasar created_at like
            if ($request->filled('tahun')) {
                $favoriteQuery->whereYear('pl.created_at', $request->tahun);
            }
            if ($request->filled('bulan')) {
                $favoriteQuery->whereMonth('pl.created_at', $request->bulan);
            }
            // filter usaha & kategori (kalau ada)
            if (!is_null($productFilterIds)) {
                $favoriteQuery->whereIn('p.id', $productFilterIds);
            }

            $favoriteRaw = $favoriteQuery
                ->select('p.nama_produk', DB::raw('COUNT(pl.id) as total_like'))
                ->groupBy('p.id', 'p.nama_produk')
                ->orderByDesc('total_like')
                ->limit(3)
                ->get();

            $produkFavoriteChart = [
                'labels' => $favoriteRaw->pluck('nama_produk'),
                'data' => $favoriteRaw->pluck('total_like'),
            ];

            // --- CHART: Top 3 Produk Dilihat (Views) ---
            $viewQuery = DB::table('produk_views as pv')
                ->join('produk as p', 'p.id', '=', 'pv.produk_id');

            if ($request->filled('tahun')) {
                $viewQuery->whereYear('pv.created_at', $request->tahun);
            }
            if ($request->filled('bulan')) {
                $viewQuery->whereMonth('pv.created_at', $request->bulan);
            }
            if (!is_null($productFilterIds)) {
                $viewQuery->whereIn('p.id', $productFilterIds);
            }

            $viewRaw = $viewQuery
                ->select('p.nama_produk', DB::raw('SUM(pv.total_klik) as total_view'))
                ->groupBy('p.id', 'p.nama_produk')
                ->orderByDesc('total_view')
                ->limit(3)
                ->get();

            $produkViewChart = [
                'labels' => $viewRaw->pluck('nama_produk'),
                'data' => $viewRaw->pluck('total_view'),
            ];

            // --- CHART: Top 3 User Aktif ---
            $userRaw = User::whereIn('id', $finalQuery->pluck('user_id')->unique())
                ->withCount([
                    'transaksi' => function ($q) use ($filteredTransactionIds) {
                        $q->whereIn('id', $filteredTransactionIds);
                    }
                ])
                ->orderByDesc('transaksi_count')
                ->limit(3)
                ->get();

            $transaksiUserChart = [
                'labels' => $userRaw->pluck('username'),
                'data' => $userRaw->pluck('transaksi_count'),
            ];

            // --- CHART: Kategori Produk (Total Terjual) Top 3 ---
            $kategoriRaw = DB::table('kategori_produk as k')
                ->join('produk as p', 'p.kategori_produk_id', '=', 'k.id')
                ->join('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
                ->whereIn('dt.transaksi_id', $filteredTransactionIds)
                ->select('k.nama_kategori_produk', DB::raw('SUM(dt.jumlah) as total_terjual'))
                ->groupBy('k.id', 'k.nama_kategori_produk')
                ->orderByDesc('total_terjual')
                ->limit(3)
                ->get();

            $kategoriChart = [
                'labels' => $kategoriRaw->pluck('nama_kategori_produk'),
                'data' => $kategoriRaw->pluck('total_terjual'),
            ];
        }

        // --- DATA LIST UNTUK FILTER (select option) ---
        $tahunList = Transaction::selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $bulanList = collect([
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ]);

        $usahaList = Usaha::orderBy('nama_usaha')->get();
        $kategoriList = DB::table('kategori_produk')->orderBy('nama_kategori_produk')->get();
        $userList = User::orderBy('username')->get();

        return view('admin.laporan.laporan', compact(
            'totalTransaksi',
            'totalPendapatan',
            'topProduk',
            'userAktif',
            'pendapatanChart',
            'produkTerlarisChart',
            'produkFavoriteChart',
            'produkViewChart',
            'transaksiUserChart',
            'kategoriChart',
            'tahunList',
            'bulanList',
            'usahaList',
            'kategoriList',
            'userList'
        ));

    }

    // === METHOD LAIN
    public function transaksiExportExcel(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        return Excel::download(
            new LaporanExport($start, $end),
            'laporan_transaksi.xlsx'
        );
    }

    public function transaksiExportPdf(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();

        $pdf = Pdf::loadView('admin.laporan.transaksi_pdf', compact('transaksi'));

        return $pdf->download('laporan_transaksi.pdf');
    }

    public function transaksi(Request $request)
    {
        // List untuk filter dropdown
        $usahaList = Usaha::orderBy('nama_usaha')->get();
        $kategoriList = DB::table('kategori_produk')
            ->orderBy('nama_kategori_produk')
            ->get();
        $userList = User::orderBy('username')->get();

        // base query: transaksi + user
        // asumsi table = 'transaksi'
        $query = Transaction::query()
            ->join('users as u', 'u.id', '=', 'transaksi.user_id')
            ->select('transaksi.*', 'u.username');

        // FILTER: tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [
                $request->start_date,
                $request->end_date,
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('transaksi.tanggal_transaksi', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('transaksi.tanggal_transaksi', '<=', $request->end_date);
        }

        // FILTER: user
        if ($request->filled('user_id')) {
            $query->where('transaksi.user_id', $request->user_id);
        }

        // FILTER: status (kalau ada kolom status di transaksi)
        if ($request->filled('status')) {
            $query->where('transaksi.status', $request->status);
        }

        // FILTER: usaha / kategori via detail_transaksi & produk
        if ($request->filled('usaha_id') || $request->filled('kategori_id')) {
            $query->join('detail_transaksi as dt', 'dt.transaksi_id', '=', 'transaksi.id')
                ->join('produk as p', 'p.id', '=', 'dt.produk_id');

            if ($request->filled('usaha_id')) {
                $query->join('usaha_produk as up', 'up.produk_id', '=', 'p.id')
                    ->where('up.usaha_id', $request->usaha_id);
            }

            if ($request->filled('kategori_id')) {
                $query->where('p.kategori_produk_id', $request->kategori_id);
            }

            // supaya 1 transaksi tidak dobel karena banyak detail
            $query->distinct();
        }

        $transaksi = $query->orderBy('transaksi.tanggal_transaksi', 'desc')->get();

        // ringkasan kecil (optional, buat atas tabel)
        $totalTransaksi = $transaksi->count();
        $totalNominal = $transaksi->sum('total');

        // daftar status, sesuaikan dengan enum di DB-mu
        $statusList = ['pending', 'paid', 'cancelled'];

        return view('admin.laporan.transaksi', compact(
            'transaksi',
            'usahaList',
            'kategoriList',
            'userList',
            'statusList',
            'totalTransaksi',
            'totalNominal'
        ));
    }

    public function pendapatanUsaha(Request $request)
    {
        $base = DB::table('usaha as u')
            ->join('usaha_produk as up', 'up.usaha_id', '=', 'u.id')
            ->join('produk as p', 'p.id', '=', 'up.produk_id')
            ->join('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->join('transaksi as t', 't.id', '=', 'dt.transaksi_id');

        // 🔎 Filter tanggal (prioritas: start/end date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $base->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [
                $request->start_date,
                $request->end_date,
            ]);
        } else {
            // fallback: filter tahun / bulan kalau dipakai dari tempat lain
            if ($request->filled('tahun')) {
                $base->whereYear('t.tanggal_transaksi', $request->tahun);
            }
            if ($request->filled('bulan')) {
                $base->whereMonth('t.tanggal_transaksi', $request->bulan);
            }
        }

        // 🔎 Filter usaha (kalau mau lihat 1 usaha saja)
        if ($request->filled('usaha_id')) {
            $base->where('u.id', $request->usaha_id);
        }

        // 🔎 Filter kategori produk
        if ($request->filled('kategori_id')) {
            $base->where('p.kategori_produk_id', $request->kategori_id);
        }

        // 📊 Data utama
        $laporan = $base->select(
            'u.id as usaha_id',
            'u.nama_usaha',
            DB::raw('COUNT(DISTINCT t.id) as total_transaksi'),
            DB::raw('SUM(dt.subtotal) as total_penjualan'),
            DB::raw('AVG(t.total) as rata_rata_transaksi'),
            DB::raw('MAX(t.tanggal_transaksi) as transaksi_terakhir')
        )
            ->groupBy('u.id', 'u.nama_usaha')
            ->orderByDesc('total_penjualan')
            ->get();

        // 🔹 Ringkasan untuk kartu di atas
        $totalUsaha = $laporan->count();
        $totalTransaksi = $laporan->sum('total_transaksi');
        $totalPendapatan = $laporan->sum('total_penjualan');
        $avgTransaksiGlobal = $laporan->avg('rata_rata_transaksi');

        // 🔹 Data untuk filter dropdown
        $usahaList = Usaha::orderBy('nama_usaha')->get();
        $kategoriList = DB::table('kategori_produk')
            ->orderBy('nama_kategori_produk')
            ->get();

        return view('admin.laporan.pendapatan_usaha', compact(
            'laporan',
            'usahaList',
            'kategoriList',
            'totalUsaha',
            'totalTransaksi',
            'totalPendapatan',
            'avgTransaksiGlobal'
        ));
    }


    public function produkTerlaris(Request $request)
    {
        // List usaha & kategori untuk filter
        $usahaList = Usaha::orderBy('nama_usaha')->get();
        $kategoriList = DB::table('kategori_produk')
            ->orderBy('nama_kategori_produk')
            ->get();

        // Base query: produk + detail_transaksi + transaksi + usaha (via usaha_produk)
        $query = DB::table('produk as p')
            ->join('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->join('transaksi as t', 't.id', '=', 'dt.transaksi_id')
            ->leftJoin('usaha_produk as up', 'up.produk_id', '=', 'p.id');

        // FILTER: Usaha
        if ($request->filled('usaha_id')) {
            $query->where('up.usaha_id', $request->usaha_id);
        }

        // FILTER: Kategori Produk
        if ($request->filled('kategori_id')) {
            $query->where('p.kategori_produk_id', $request->kategori_id);
        }

        // FILTER: Tanggal transaksi (start/end)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [
                $request->start_date,
                $request->end_date,
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('t.tanggal_transaksi', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('t.tanggal_transaksi', '<=', $request->end_date);
        }

        // Aggregasi per produk
        $laporan = $query->select(
            'p.id',
            'p.nama_produk',
            DB::raw('SUM(dt.jumlah) as total_terjual')
        )
            ->groupBy('p.id', 'p.nama_produk')
            ->orderByDesc('total_terjual')
            ->get();

        // Ringkasan
        $totalProduk = $laporan->count();
        $totalTerjual = $laporan->sum('total_terjual');
        $topRow = $laporan->first();

        // Data khusus untuk grafik (misal top 10 saja)
        $chartData = $laporan->take(10);

        return view('admin.laporan.produk_terlaris', compact(
            'laporan',
            'usahaList',
            'kategoriList',
            'totalProduk',
            'totalTerjual',
            'topRow',
            'chartData'
        ));
    }

    public function produkSlowMoving(Request $request)
    {
        // 🔹 Ambil rentang tanggal (kalau kosong, default 30 hari terakhir)
        $start = $request->start_date;
        $end = $request->end_date;

        if (!$start || !$end) {
            $start = now()->subDays(30)->toDateString();
            $end = now()->toDateString();
        }

        // Batas slow moving (misal: total terjual < 5)
        $threshold = 5;

        $base = DB::table('produk as p')
            ->leftJoin('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->leftJoin('transaksi as t', 't.id', '=', 'dt.transaksi_id')
            ->leftJoin('usaha_produk as up', 'up.produk_id', '=', 'p.id')
            ->leftJoin('usaha as u', 'u.id', '=', 'up.usaha_id');

        // 🔎 Filter usaha
        if ($request->filled('usaha_id')) {
            $base->where('u.id', $request->usaha_id);
        }

        // 🔎 Filter kategori produk
        if ($request->filled('kategori_id')) {
            $base->where('p.kategori_produk_id', $request->kategori_id);
        }

        // 🔎 Filter tanggal (pakai tanggal_transaksi, tapi tetap include produk tanpa transaksi di periode tsb)
        $base->where(function ($q) use ($start, $end) {
            $q->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [$start, $end])
                ->orWhereNull('t.id');
        });

        // 📊 Data utama slow moving
        $laporan = $base->select(
            'p.id',
            'p.nama_produk',
            'u.nama_usaha',
            DB::raw('IFNULL(SUM(dt.jumlah),0) as total_terjual'),
            DB::raw('MAX(t.tanggal_transaksi) as transaksi_terakhir')
        )
            ->groupBy('p.id', 'p.nama_produk', 'u.nama_usaha')
            ->havingRaw('total_terjual < ?', [$threshold])
            ->orderBy('total_terjual', 'asc')
            ->orderBy('p.nama_produk')
            ->limit(50) // 🔸 Biar nggak kepanjangan, ambil maksimal 50 produk slow
            ->get();

        // 🔹 Ringkasan
        $totalProdukSlow = $laporan->count();
        $totalQtyTerjual = $laporan->sum('total_terjual');

        // 🔹 Data untuk filter dropdown
        $usahaList = Usaha::orderBy('nama_usaha')->get();
        $kategoriList = DB::table('kategori_produk')
            ->orderBy('nama_kategori_produk')
            ->get();

        return view('admin.laporan.produk_slow_moving', compact(
            'laporan',
            'usahaList',
            'kategoriList',
            'start',
            'end',
            'threshold',
            'totalProdukSlow',
            'totalQtyTerjual'
        ));
    }

    public function transaksiUser(Request $request)
    {
        // List kategori & usaha untuk dropdown
        $kategoriList = DB::table('kategori_produk')
            ->orderBy('nama_kategori_produk')
            ->get();

        $usahaList = Usaha::orderBy('nama_usaha')->get();

        // Base query: user + transaksi + detail + produk (+ usaha_produk)
        $query = DB::table('users as u')
            ->join('transaksi as t', 't.user_id', '=', 'u.id')
            ->join('detail_transaksi as dt', 'dt.transaksi_id', '=', 't.id')
            ->join('produk as p', 'p.id', '=', 'dt.produk_id')
            ->leftJoin('usaha_produk as up', 'up.produk_id', '=', 'p.id');

        // FILTER: Usaha
        if ($request->filled('usaha_id')) {
            $query->where('up.usaha_id', $request->usaha_id);
        }

        // FILTER: Kategori
        if ($request->filled('kategori_id')) {
            $query->where('p.kategori_produk_id', $request->kategori_id);
        }

        // FILTER: Tanggal transaksi (start/end)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [
                $request->start_date,
                $request->end_date,
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('t.tanggal_transaksi', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('t.tanggal_transaksi', '<=', $request->end_date);
        }

        // Aggregasi per user
        $laporan = $query->select(
            'u.id as user_id',
            'u.username',
            DB::raw('COUNT(DISTINCT t.id) as total_transaksi'),
            DB::raw('SUM(dt.subtotal) as total_belanja')
        )
            ->groupBy('u.id', 'u.username')
            ->orderByDesc('total_belanja')
            ->get();

        // Ringkasan
        $totalUser = $laporan->count();
        $totalTransaksi = $laporan->sum('total_transaksi');
        $totalBelanja = $laporan->sum('total_belanja');

        return view('admin.laporan.transaksi_user', compact(
            'laporan',
            'kategoriList',
            'usahaList',
            'totalUser',
            'totalTransaksi',
            'totalBelanja'
        ));
    }
    public function kategoriProduk(Request $request)
    {
        $laporanQuery = DB::table('kategori_produk as k')
            ->join('produk as p', 'p.kategori_produk_id', '=', 'k.id')
            ->leftJoin('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->leftJoin('transaksi as t', 't.id', '=', 'dt.transaksi_id')
            ->leftJoin('usaha_produk as up', 'up.produk_id', '=', 'p.id')
            ->leftJoin('usaha as u', 'u.id', '=', 'up.usaha_id');

        // Filter Usaha
        if ($request->filled('usaha_id')) {
            $laporanQuery->where('u.id', $request->usaha_id);
        }

        // Filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $laporanQuery->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [
                $request->start_date,
                $request->end_date,
            ]);
        }

        $laporan = $laporanQuery
            ->select(
                'k.id',
                'k.nama_kategori_produk',
                DB::raw('COUNT(DISTINCT p.id) as total_produk'),
                DB::raw('IFNULL(SUM(dt.jumlah),0) as total_terjual')
            )
            ->groupBy('k.id', 'k.nama_kategori_produk')
            ->orderByDesc('total_terjual')
            ->get();

        $usahaList = Usaha::orderBy('nama_usaha')->get();

        return view('admin.laporan.kategori_produk', compact('laporan', 'usahaList'));
    }

    // 7. Laporan Produk Favorite (berdasarkan LIKE)
    public function produkFavorite(Request $request)
    {
        // total semua produk (buat ringkasan)
        $totalProduk = Produk::count();

        // base query: like per produk
        $query = DB::table('produk_likes as pl')
            ->join('produk as p', 'p.id', '=', 'pl.produk_id')
            // join ke usaha_produk untuk bisa filter usaha
            ->leftJoin('usaha_produk as up', 'up.produk_id', '=', 'p.id');

        // FILTER: USaha
        if ($request->filled('usaha_id')) {
            $query->where('up.usaha_id', $request->usaha_id);
        }

        // FILTER: Tanggal dari tabel produk_likes
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(pl.created_at)'), [
                $request->start_date,
                $request->end_date,
            ]);
        }

        // agregasi like per produk
        $laporan = $query->select(
            'p.id',
            'p.nama_produk',
            DB::raw('COUNT(pl.id) as total_like')
        )
            ->groupBy('p.id', 'p.nama_produk')
            ->orderByDesc('total_like')
            ->get();

        // list usaha buat dropdown filter
        $usahaList = Usaha::orderBy('nama_usaha')->get();

        return view('admin.laporan.produk_favorite', compact(
            'laporan',
            'totalProduk',
            'usahaList'
        ));
    }


    // 8. Laporan Produk Dilihat (berdasarkan views)
    public function produkViews(Request $request)
    {
        // === TOTAL PRODUK (semua produk di sistem, tanpa filter) ===
        $totalProduk = Produk::count();

        // === BASE QUERY: agregasi views per produk dari produk_views ===
        $query = DB::table('produk as p')
            ->leftJoin('produk_views as pv', 'pv.produk_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.nama_produk',
                DB::raw('COALESCE(SUM(pv.total_klik), 0) as total_views')
            );

        // === FILTER USAHA (pakai pivot usaha_produk) ===
        if ($request->filled('usaha_id')) {
            $query->join('usaha_produk as up', 'up.produk_id', '=', 'p.id')
                ->where('up.usaha_id', $request->usaha_id);
        }

        // === FILTER TANGGAL (pakai created_at dari produk_views) ===
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;

            // pakai DATE() biar aman (hanya tanggal)
            $query->whereBetween(DB::raw('DATE(pv.created_at)'), [$start, $end]);
        }

        // GROUP & ORDER
        $query->groupBy('p.id', 'p.nama_produk')
            ->orderByDesc('total_views');

        $produkViews = $query->get();

        // === RINGKASAN BERDASARKAN HASIL AGREGASI ===
        $produkDenganViews = $produkViews->where('total_views', '>', 0)->count();
        $totalViews = $produkViews->sum('total_views');

        // === DATA UNTUK DROPDOWN FILTER ===
        $usahaList = Usaha::orderBy('nama_usaha')->get();

        return view('admin.laporan.produk_views', compact(
            'produkViews',
            'totalProduk',
            'produkDenganViews',
            'totalViews',
            'usahaList'
        ));
    }

}
