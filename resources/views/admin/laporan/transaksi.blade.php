@extends('adminlte::page')
@section('title','Semua Transaksi')
@section('content_header')
    <h1>Semua Transaksi</h1>
@stop
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>User</th><th>Total</th><th>Tanggal</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->user_id }}</td>
                <td>{{ number_format($t->total) }}</td>
                <td>{{ $t->tanggal_transaksi }}</td>
                <td>{{ $t->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop
