@extends('guest.layouts.main')
@section('title', 'Produk')
@section('content')

    <!-- ***** Main Banner Area Start ***** -->
    <div class="page-heading" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-content">
                        <h2>Detail Produk</h2>
                        <span>{{ $produk->nama_produk }} - Temukan informasi lengkap dan mulai belanja</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->


    <!-- ***** Product Area Starts ***** -->
    <section class="section" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="left-images">
                        <div id="produkCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($produk->fotoProduk as $index => $foto)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $foto->file_foto_produk) }}"
                                            class="d-block img-fluid" alt="{{ $produk->nama_produk }}">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Tombol sebelumnya -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#produkCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Sebelumnya</span>
                            </button>
                            <!-- Tombol selanjutnya -->
                            <button class="carousel-control-next" type="button" data-bs-target="#produkCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Selanjutnya</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="right-content">
                        <h4>{{ $produk->nama_produk }}</h4>
                        {{-- <span class="category">Category: <a href="#">{{ $produk->kategori->nama_kategori_produk }}</a></span> --}}
                        <span class="price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                        <ul class="stars">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <p>{{ $produk->deskripsi }}</p>
                        {{-- <div class="quantity-content">
                            <div class="left-content">
                                <h6>Jumlah pesanan</h6>
                            </div>
                            <div class="right-content">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus"><input type="number" step="1"
                                        min="1" max="" name="quantity" value="1" title="Qty"
                                        class="input-text qty text" size="4" pattern="" inputmode=""><input
                                        type="button" value="+" class="plus">
                                </div>
                            </div>
                        </div>
                        <div class="total">
                            <h4 id="total-harga">Rp 0</h4>
                            <div class="main-border-button">
                                <div class="main-border-button"><a href="#">Add To Cart</a></div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Product Area Ends ***** -->

    <script>
        const hargaProduk = {{ $produk->harga }};
        const qtyInput = document.querySelector('input[name="quantity"]');
        const totalElement = document.querySelector('.total h4');
        const minusBtn = document.querySelector('.minus');
        const plusBtn = document.querySelector('.plus');

        function updateTotal() {
            const qty = parseInt(qtyInput.value) || 1;
            const total = hargaProduk * qty;
            totalElement.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        minusBtn.addEventListener('click', () => {
            let current = parseInt(qtyInput.value);
            if (current > 1) {
                qtyInput.value = current - 1;
                updateTotal();
            }
        });

        plusBtn.addEventListener('click', () => {
            let current = parseInt(qtyInput.value);
            qtyInput.value = current + 1;
            updateTotal();
        });

        qtyInput.addEventListener('input', updateTotal); // kalau user edit langsung

        // Update pertama kali
        updateTotal();
    </script>

    <style>
        #produkCarousel .carousel-inner {
            max-height: 600px;
            /* Atur tinggi carousel */
        }

        #produkCarousel .carousel-item img {
            width: 80%;
            /* Ukuran gambar disesuaikan */
            height: auto;
            /* Menjaga rasio gambar */
            margin-left: auto;
            margin-right: auto;
        }

        /* Atur tombol navigasi agar sejajar di tengah */
        .carousel-control-prev,
        .carousel-control-next {
            top: 50%;
            transform: translateY(-50%);
        }

        /* Ganti warna panah menjadi hitam */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: black;
        }
    </style>

@endsection
