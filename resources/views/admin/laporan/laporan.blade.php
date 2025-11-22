@extends('adminlte::page')

@section('title', 'Laporan')

@section('content_header')
    <h1>Laporan Admin</h1>
@stop

@section('content')
    <div class="list-group">
        <a href="{{ route('admin.laporan.transaksi') }}" class="list-group-item list-group-item-action">Semua Transaksi</a>
        <a href="{{ route('admin.laporan.pendapatanUsaha') }}" class="list-group-item list-group-item-action">Pendapatan Per Usaha</a>
        <a href="{{ route('admin.laporan.produkTerlaris') }}" class="list-group-item list-group-item-action">Produk Terlaris</a>
        <a href="{{ route('admin.laporan.produkSlowMoving') }}" class="list-group-item list-group-item-action">Produk Slow Moving</a>
        <a href="{{ route('admin.laporan.transaksiUser') }}" class="list-group-item list-group-item-action">Transaksi Per User</a>
        <a href="{{ route('admin.laporan.kategoriProduk') }}" class="list-group-item list-group-item-action">Kategori Produk</a>
       <a href="{{ route('admin.laporan.produkViews') }}" class="list-group-item list-group-item-action">Views</a>

@stop
