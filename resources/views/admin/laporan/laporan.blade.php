@extends('adminlte::page')

@section('title', 'Dashboard Laporan')

@section('css')
<style>
    body {
        background: #0b1d39 !important;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 14px;
    }

    .card-modern {
        background: #102544 !important;
        border-radius: 14px !important;
        border: 1px solid rgba(255,255,255,0.05);
        box-shadow: 0px 4px 12px rgba(0,0,0,0.3);
        color: #e8eef7;
        padding: 18px;
    }

    .metric-value {
        font-size: 32px;
        font-weight: 700;
        margin-top: 5px;
    }

    .metric-label {
        font-size: 14px;
        opacity: .8;
    }

    .chart-box {
        height: 260px;
    }

    /* Sidebar laporan */
    .report-nav {
        background: #0f233f;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 18px;
    }

    .report-nav a {
        display: block;
        padding: 10px 14px;
        color: #b8ccdf;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 6px;
        text-decoration: none;
        transition: 0.2s;
    }

    .report-nav a:hover {
        background: rgba(255,255,255,0.07);
        color: white;
    }

</style>
@stop

@section('content_header')
<h1 style="color:white; font-weight:600;">Dashboard Laporan</h1>
@stop

@section('content')

{{-- === NAVIGASI LAPORAN === --}}
<div class="report-nav">
    <a href="{{ route('admin.laporan.transaksi') }}">📄 Semua Transaksi</a>
    <a href="{{ route('admin.laporan.pendapatanUsaha') }}">💰 Pendapatan Per Usaha</a>
    <a href="{{ route('admin.laporan.produkTerlaris') }}">🔥 Produk Terlaris</a>
    <a href="{{ route('admin.laporan.produkSlowMoving') }}">🐌 Produk Slow Moving</a>
    <a href="{{ route('admin.laporan.transaksiUser') }}">👥 Transaksi Per User</a>
    <a href="{{ route('admin.laporan.kategoriProduk') }}">📦 Kategori Produk</a>
</div>

{{-- ==== GRID DASHBOARD UTAMA ==== --}}
<div class="dashboard-grid">

    {{-- Kartu Metrik --}}
    <div class="card-modern" style="grid-column: span 3;">
        <div class="metric-label">Total Transaksi</div>
        <div class="metric-value">{{ $totalTransaksi ?? 0 }}</div>
    </div>

    <div class="card-modern" style="grid-column: span 3;">
        <div class="metric-label">Total Pendapatan</div>
        <div class="metric-value">Rp {{ number_format($totalPendapatan ?? 0,0,',','.') }}</div>
    </div>

    <div class="card-modern" style="grid-column: span 3;">
        <div class="metric-label">Produk Terlaris</div>
        <div class="metric-value">{{ $topProduk ?? '-' }}</div>
    </div>

    <div class="card-modern" style="grid-column: span 3;">
        <div class="metric-label">User Aktif</div>
        <div class="metric-value">{{ $userAktif ?? '-' }}</div>
    </div>

    {{-- Grafik Lebar 6 Kolom --}}
    <div class="card-modern" style="grid-column: span 6;">
        <h5>📈 Transaksi Mingguan</h5>
        <div class="chart-box"><canvas id="chartTransaksi"></canvas></div>
    </div>

    <div class="card-modern" style="grid-column: span 6;">
        <h5>💰 Pendapatan Top 5 Usaha</h5>
        <div class="chart-box"><canvas id="chartPendapatan"></canvas></div>
    </div>

    {{-- Grafik Lebar 4 Kolom --}}
    <div class="card-modern" style="grid-column: span 4;">
        <h5>🔥 Top 5 Produk Terlaris</h5>
        <div class="chart-box"><canvas id="chartTerlaris"></canvas></div>
    </div>

    <div class="card-modern" style="grid-column: span 4;">
        <h5>🐌 Produk Slow Moving</h5>
        <div class="chart-box"><canvas id="chartSlow"></canvas></div>
    </div>

    <div class="card-modern" style="grid-column: span 4;">
        <h5>👥 Top 5 User Aktif</h5>
        <div class="chart-box"><canvas id="chartUser"></canvas></div>
    </div>

    {{-- Donut Kategori --}}
    <div class="card-modern" style="grid-column: span 4;">
        <h5>📦 Kategori Produk</h5>
        <div class="chart-box"><canvas id="chartKategori"></canvas></div>
    </div>

</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

let data = {
    transaksi: @json($transaksiChart),
    pendapatan: @json($pendapatanChart),
    terlaris: @json($produkTerlarisChart),
    slow: @json($produkSlowChart),
    user: @json($transaksiUserChart),
    kategori: @json($kategoriChart),
};

function chart(id, type, labels, data){
    new Chart(document.getElementById(id), {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd']
            }]
        },
        options: { responsive:true, maintainAspectRatio:false }
    });
}

chart('chartTransaksi','line', data.transaksi.labels, data.transaksi.data);
chart('chartPendapatan','bar', data.pendapatan.labels, data.pendapatan.data);
chart('chartTerlaris','bar', data.terlaris.labels, data.terlaris.data);
chart('chartSlow','pie', data.slow.labels, data.slow.data);
chart('chartUser','bar', data.user.labels, data.user.data);
chart('chartKategori','doughnut', data.kategori.labels, data.kategori.data);

</script>
    <div class="list-group">
        <a href="{{ route('admin.laporan.transaksi') }}" class="list-group-item list-group-item-action">Semua Transaksi</a>
        <a href="{{ route('admin.laporan.pendapatanUsaha') }}" class="list-group-item list-group-item-action">Pendapatan Per Usaha</a>
        <a href="{{ route('admin.laporan.produkTerlaris') }}" class="list-group-item list-group-item-action">Produk Terlaris</a>
        <a href="{{ route('admin.laporan.produkSlowMoving') }}" class="list-group-item list-group-item-action">Produk Slow Moving</a>
        <a href="{{ route('admin.laporan.transaksiUser') }}" class="list-group-item list-group-item-action">Transaksi Per User</a>
        <a href="{{ route('admin.laporan.kategoriProduk') }}" class="list-group-item list-group-item-action">Kategori Produk</a>
       <a href="{{ route('admin.laporan.produkViews') }}" class="list-group-item list-group-item-action">Views</a>

@stop
