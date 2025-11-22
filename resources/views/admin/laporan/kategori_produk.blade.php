@extends('adminlte::page')
@section('title','Kategori Produk')
@section('content_header')
    <h1>Kategori Produk</h1>
@stop
@section('content')
    <table class="table table-bordered">
        <thead><tr><th>Kategori</th><th>Total Produk</th><th>Total Terjual</th></tr></thead>
        <tbody>
            @foreach($laporan as $l)
            <tr><td>{{ $l->nama_kategori_produk }}</td><td>{{ $l->total_produk }}</td><td>{{ $l->total_terjual }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <canvas id="kategoriChart"></canvas>
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('kategoriChart').getContext('2d'),{
    type:'bar',
    data:{
        labels:{!! json_encode($laporan->pluck('nama_kategori_produk')) !!},
        datasets:[{
            label:'Total Terjual',
            data:{!! json_encode($laporan->pluck('total_terjual')) !!},
            backgroundColor:'rgba(75,192,192,0.5)',
            borderColor:'rgba(75,192,192,1)',
            borderWidth:1
        }]
    },
    options:{responsive:true}
});
</script>
@stop
