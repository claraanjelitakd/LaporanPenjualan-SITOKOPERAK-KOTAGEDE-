@extends('layouts.app') {{-- kalau ada layout, kalau nggak bisa langsung aja --}}

@section('content')
<div class="container">
    <h2>Daftar Transaksi</h2>

    @foreach ($transaksi as $t)
        <div class="card mb-3">
            <div class="card-body">
                <h5>Transaksi ID: {{ $t->id }} | Oleh: {{ $t->user->name }}</h5>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($t->details as $detail)
                            <tr>
                                <td>{{ $detail->produk->nama_produk }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <strong>Total: Rp {{ number_format($t->total, 0, ',', '.') }}</strong>
            </div>
        </div>
    @endforeach
</div>
@endsection
