@extends('adminlte::page')
@section('title','Pendapatan Usaha')
@section('content_header')
    <h1>Pendapatan Per Usaha</h1>
@stop
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr><th>Usaha</th><th>Total Transaksi</th><th>Total Penjualan</th><th>Rata-rata Transaksi</th><th>Terakhir</th></tr>
        </thead>
        <tbody>
            @foreach($laporan as $l)
            <tr>
                <td>{{ $l->nama_usaha }}</td>
                <td>{{ $l->total_transaksi }}</td>
                <td>{{ number_format($l->total_penjualan) }}</td>
                <td>{{ number_format($l->rata_rata_transaksi) }}</td>
                <td>{{ $l->transaksi_terakhir }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <canvas id="pendapatanChart"></canvas>
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('pendapatanChart').getContext('2d');
new Chart(ctx,{
    type:'bar',
    data:{
        labels: {!! json_encode($laporan->pluck('nama_usaha')) !!},
        datasets:[{
            label:'Total Penjualan',
            data:{!! json_encode($laporan->pluck('total_penjualan')) !!},
            backgroundColor:'rgba(54,162,235,0.5)',
            borderColor:'rgba(54,162,235,1)',
            borderWidth:1
        }]
    },
    options:{responsive:true}
});
</script>
@stop
