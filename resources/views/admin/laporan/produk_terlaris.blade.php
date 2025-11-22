@extends('adminlte::page')
@section('title','Produk Terlaris')
@section('content_header')
    <h1>Produk Terlaris</h1>
@stop
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr><th>Produk</th><th>Total Terjual</th></tr>
        </thead>
        <tbody>
            @foreach($laporan as $l)
            <tr>
                <td>{{ $l->nama_produk }}</td>
                <td>{{ $l->total_terjual }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <canvas id="produkTerlarisChart"></canvas>
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('produkTerlarisChart').getContext('2d'),{
    type:'bar',
    data:{
        labels:{!! json_encode($laporan->pluck('nama_produk')) !!},
        datasets:[{
            label:'Total Terjual',
            data:{!! json_encode($laporan->pluck('total_terjual')) !!},
            backgroundColor:'rgba(255,99,132,0.5)',
            borderColor:'rgba(255,99,132,1)',
            borderWidth:1
        }]
    },
    options:{responsive:true}
});
</script>
@stop
