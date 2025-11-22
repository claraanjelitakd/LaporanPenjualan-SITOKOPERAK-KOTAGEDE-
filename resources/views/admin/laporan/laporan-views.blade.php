@extends('adminlte::page')

@section('title', 'Laporan Views Produk')

@section('content_header')
    <h1>Laporan Views Produk</h1>
@stop

@section('content')
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Total Views</th>
            <th>Total Likes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produks as $produk)
        <tr>
            <td>{{ $produk->nama_produk }}</td>
            <td>{{ $produk->views }}</td>
            <td>{{ $produk->likes }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
