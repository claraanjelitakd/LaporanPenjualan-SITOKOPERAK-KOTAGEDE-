<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Usaha;
use App\Models\Produk;

class LaporanController extends Controller
{
    // di LaporanController
    public function index()
    {
        return view('admin.laporan.laporan'); // path ke laporan.blade.php
    }

    // 1. Laporan semua transaksi
    public function transaksi(Request $request)
    {
        $query = Transaction::query();

        // filter by tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();

        return view('admin.laporan.transaksi', compact('transaksi'));
    }

    // 2. Laporan pendapatan per usaha
    public function pendapatanUsaha()
    {
        $laporan = DB::table('usaha as u')
            ->join('usaha_produk as up', 'up.usaha_id', '=', 'u.id')
            ->join('produk as p', 'p.id', '=', 'up.produk_id')
            ->join('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->join('transaksi as t', 't.id', '=', 'dt.transaksi_id')
            ->select(
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

        return view('admin.laporan.pendapatan_usaha', compact('laporan'));
    }

    // 3. Laporan produk terlaris
    public function produkTerlaris()
    {
        $laporan = DB::table('produk as p')
            ->join('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.nama_produk',
                DB::raw('SUM(dt.jumlah) as total_terjual')
            )
            ->groupBy('p.id', 'p.nama_produk')
            ->orderByDesc('total_terjual')
            ->get();

        return view('admin.laporan.produk_terlaris', compact('laporan'));
    }

    // 4. Laporan produk slow moving
    public function produkSlowMoving()
    {
        $laporan = DB::table('produk as p')
            ->leftJoin('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.nama_produk',
                DB::raw('IFNULL(SUM(dt.jumlah),0) as total_terjual')
            )
            ->groupBy('p.id', 'p.nama_produk')
            ->havingRaw('total_terjual < ?', [5])
            ->orderBy('total_terjual', 'asc')
            ->get();

        return view('admin.laporan.produk_slow_moving', compact('laporan'));
    }

    // 5. Laporan transaksi per user
    public function transaksiUser()
    {
        $laporan = DB::table('users as u')
            ->join('transaksi as t', 't.user_id', '=', 'u.id')
            ->select(
                'u.id as user_id',
                'u.username',
                DB::raw('COUNT(t.id) as total_transaksi'),
                DB::raw('SUM(t.total) as total_belanja')
            )
            ->groupBy('u.id', 'u.username')
            ->orderByDesc('total_belanja')
            ->get();

        return view('admin.laporan.transaksi_user', compact('laporan'));
    }

    // 6. Laporan kategori produk
    public function kategoriProduk()
    {
        $laporan = DB::table('kategori_produk as k')
            ->join('produk as p', 'p.kategori_produk_id', '=', 'k.id')
            ->leftJoin('detail_transaksi as dt', 'dt.produk_id', '=', 'p.id')
            ->select(
                'k.id',
                'k.nama_kategori_produk',
                DB::raw('COUNT(p.id) as total_produk'),
                DB::raw('IFNULL(SUM(dt.jumlah),0) as total_terjual')
            )
            ->groupBy('k.id', 'k.nama_kategori_produk')
            ->orderByDesc('total_terjual')
            ->get();

        return view('admin.laporan.kategori_produk', compact('laporan'));
    }
}
