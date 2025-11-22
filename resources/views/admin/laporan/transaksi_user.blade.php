@extends('adminlte::page')
@section('title','Transaksi User')
@section('content_header')
    <h1>Transaksi Per User</h1>
@stop
@section('content')
    <table class="table table-bordered">
        <thead><tr><th>User</th><th>Total Transaksi</th><th>Total Belanja</th></tr></thead>
        <tbody>
            @foreach($laporan as $l)
            <tr><td>{{ $l->username }}</td><td>{{ $l->total_transaksi }}</td><td>{{ number_format($l->total_belanja) }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <canvas id="transaksiUserChart"></canvas>
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('transaksiUserChart').getContext('2d'),{
    type:'bar',
    data:{
        labels:{!! json_encode($laporan->pluck('username')) !!},
        datasets:[{
            label:'Total Belanja',
            data:{!! json_encode($laporan->pluck('total_belanja')) !!},
            backgroundColor:'rgba(153,102,255,0.5)',
            borderColor:'rgba(153,102,255,1)',
            borderWidth:1
        }]
    },
    options:{responsive:true}
});
</script>
@stop
