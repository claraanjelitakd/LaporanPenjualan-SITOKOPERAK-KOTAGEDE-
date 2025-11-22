@extends('guest.layouts.main')
@section('title', 'Berbagai Macam Produk')
@section('content')

    <!-- ***** Main Banner Area Start ***** -->
    <div class="page-heading" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-content">
                        <h2>Temukan Produk Favoritmu!</h2>
                        <span>Pilihan lengkap & harga terbaik hanya di toko kami</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** Products Area Starts ***** -->
    <section class="section" id="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Produk Terbaru Kami!</h2>
                        <span>Temukan produk yang kamu suka!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @if ($produks->count() > 0)
                    @foreach ($produks as $produk)
                        <div class="col-lg-4">
                            <div class="item">
                                <div class="thumb">
                                    <div class="hover-content">
                                        <ul>
                                            <li>
                                                <a href="{{ route('guest-singleProduct', $produk->slug) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </li>
                                            <li><a href=""><i class="fa fa-star"></i></a></li>
                                            <li><a href=""><i class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>

                                    <!-- PERBAIKAN DI SINI -->
                                    <a href="{{ route('guest-singleProduct', $produk->slug) }}" class="img-link">
                                        <img src="{{ asset('storage/' . (optional($produk->fotoProduk->first())->file_foto_produk ?? 'placeholder.jpg')) }}"
                                            alt="{{ $produk->nama_produk }}">
                                    </a>
                                </div>

                                <div class="down-content">
                                    <h4><a
                                            href="{{ route('guest-singleProduct', $produk->slug) }}">{{ $produk->nama_produk }}</a>
                                    </h4>
                                    <span>Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                    <ul class="stars">
                                        @for ($i = 0; $i < 5; $i++)
                                            <li><i class="fa fa-star"></i></li>
                                        @endfor
                                    </ul>
                                    <p>{{ $produk->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <h5>Produk belum tersedia saat ini.</h5>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- ***** Products Area Ends ***** -->
@endsection

<style>
    .thumb {
        position: relative;
        overflow: hidden;
        border-radius: 6px;
    }

    .thumb a.img-link {
        position: relative;
        z-index: 1;
        display: block;
    }

    .thumb a.img-link img {
        display: block;
        width: 100%;
        height: auto;
        transition: transform .35s ease;
    }

    /* Overlay */
    .thumb .hover-content {
        position: absolute;
        inset: 0;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        background: rgba(0, 0, 0, 0.25);
        opacity: 0;
        transform: translateY(10px);
        transition: opacity .25s ease, transform .25s ease;
        pointer-events: none;
        /* tetap NONE supaya klik bisa tembus */
    }

    /* ICON AGAR MASIH BISA DIKLIK */
    .thumb .hover-content a,
    .thumb .hover-content button {
        pointer-events: auto;
        /* aktif hanya untuk icon */
        color: #fff;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        padding: .6rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        font-size: 1rem;
        transition: background .15s ease, transform .15s ease;
    }

    .thumb:hover .hover-content,
    .thumb:focus-within .hover-content {
        opacity: 1;
        transform: translateY(0);
    }

    .thumb:hover a.img-link img,
    .thumb:focus-within a.img-link img {
        transform: scale(1.05);
    }
</style>
