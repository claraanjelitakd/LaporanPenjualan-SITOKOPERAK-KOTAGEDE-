@extends('guest.layouts.main')
@section('title', 'Index')
@section('content')

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="left-content">
                        <div class="thumb">
                            <div class="inner-content">
                                <h4>Jogja Istimewah</h4>
                                <span>Temukan produk-produk istimewah kami!</span>
                                <div class="main-border-button">
                                    <a href="#">Beli Sekarang!</a>
                                </div>
                            </div>
                            <img src="assets/images/malioboro2.jpg" alt="Keraton Yogyakarta" width="790" height="688">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-content">
                        <div class="row">
                            @foreach ($randomKategoris as $kategori)
                                <div class="col-lg-6">
                                    <div class="right-first-image">
                                        <div class="thumb">
                                            <div class="inner-content">
                                                <h4>{{ $kategori->nama_kategori_produk }}</h4>
                                                <span>Produk Bagus untuk {{ $kategori->nama_kategori_produk }}</span>
                                            </div>
                                            <div class="hover-content">
                                                <div class="inner">
                                                    <h4>{{ $kategori->nama_kategori_produk }}</h4>
                                                    <!-- Ini bisa juga diganti kalau mau lebih dinamis -->
                                                    <p>Temukan keindahan alami dalam setiap
                                                        {{ strtolower($kategori->nama_kategori_produk) }}!</p>
                                                    <div class="main-border-button">
                                                        <a href="{{ route('guest-productsByCategory', $kategori->slug) }}">Cari
                                                            Disini</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="{{ asset('assets/images/' . $kategori->slug . '.jpg') }}"
                                                alt="{{ $kategori->nama_kategori_produk }}" class="fixed-width-img"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/images/kategori-default.jpg') }}';"
                                                width="385" height="330">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** Produk Area Starts ***** -->
    <section class="section" id="men">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Produk Terbaru Kami!</h2>
                        <span>Temukan Produk Terfavoritmu!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="men-item-carousel">
                        <div class="owl-men-item owl-carousel">
                            @foreach ($randomProduks as $produk)
                                <div class="item">
                                    <div class="thumb">
                                        <div class="hover-content">
                                            <ul>
                                                <li><a href="{{ route('guest-singleProduct', $produk->slug) }}"><i
                                                            class="fa fa-eye"></i></a></li>
                                                <li>
                                                    <button type="button" class="like-btn" data-id="{{ $produk->id }}">
                                                        <i
                                                            class="fa fa-star star-icon {{ $produk->likes_count > 0 ? 'active' : '' }}"></i>
                                                    </button>
                                                    {{-- <a href="#" class="like-btn" data-id="{{ $produk->id }}"
                                                        data-liked="{{ $produk->is_liked ? '1' : '0' }}">
                                                        <i class="fa fa-star {{ $produk->is_liked ? 'liked' : '' }}"></i>
                                                        <span class="like-count">{{ $produk->likes_count }}</span>
                                                    </a> --}}
                                                </li>

                                                <li><a href=""><i class="fa fa-shopping-cart"></i></a></li>
                                            </ul>
                                        </div>
                                        <img src="{{ asset('storage/' . optional($produk->fotoProduk->first())->file_foto_produk) }}"
                                            alt="{{ $produk->nama_produk }}"
                                            onerror="this.onerror=null;this.src='{{ asset('images/produk-default.jpg') }}';">
                                    </div>
                                    <div class="down-content">
                                        <h4>{{ $produk->nama_produk }}</h4>
                                        <span>Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                        <ul class="stars">
                                            @for ($i = 0; $i < 5; $i++)
                                                <li><i class="fa fa-star"></i></li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <style>
        .thumb {
            position: relative;
            overflow: hidden;
            border-radius: 6px;
        }

        .star-icon {
            transition: .2s ease;
            color: white;
        }

        .star-icon.active {
            color: #ffc107 !important;
        }
    </style>

    {{-- ========== JS ========== --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.like-btn').forEach(btn => {

                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const productId = this.dataset.id;
                    const icon = this.querySelector('.star-icon');

                    fetch(`/produk/${productId}/like`, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.liked) {
                                icon.classList.add('active');
                            } else {
                                icon.classList.remove('active');
                            }
                        })
                        .catch(err => console.error(err));
                });

            });
        });
    </script>
    <!-- ***** Produk Area Ends ***** -->

    {{-- <!-- ***** Social Area Starts ***** -->
    <section class="section" id="social">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Social Media</h2>
                        <span>Details to details is what makes Hexashop different from the other themes.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row images">
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Fashion</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-01.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>New</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-02.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Brand</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-03.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Makeup</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-04.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Leather</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-05.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Bag</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-06.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Social Area Ends ***** -->

    <!-- ***** Subscribe Area Starts ***** -->
    <div class="subscribe">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="section-heading">
                        <h2>By Subscribing To Our Newsletter You Can Get 30% Off</h2>
                        <span>Details to details is what makes Hexashop different from the other themes.</span>
                    </div>
                    <form id="subscribe" action="" method="get">
                        <div class="row">
                            <div class="col-lg-5">
                                <fieldset>
                                    <input name="name" type="text" id="name" placeholder="Your Name"
                                        required="">
                                </fieldset>
                            </div>
                            <div class="col-lg-5">
                                <fieldset>
                                    <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*"
                                        placeholder="Your Email Address" required="">
                                </fieldset>
                            </div>
                            <div class="col-lg-2">
                                <fieldset>
                                    <button type="submit" id="form-submit" class="main-dark-button"><i
                                            class="fa fa-paper-plane"></i></button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li>Store Location:<br><span>Sunny Isles Beach, FL 33160, United States</span></li>
                                <li>Phone:<br><span>010-020-0340</span></li>
                                <li>Office Location:<br><span>North Miami Beach</span></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                                <li>Work Hours:<br><span>07:30 AM - 9:30 PM Daily</span></li>
                                <li>Email:<br><span>info@company.com</span></li>
                                <li>Social Media:<br><span><a href="#">Facebook</a>, <a
                                            href="#">Instagram</a>, <a href="#">Behance</a>, <a
                                            href="#">Linkedin</a></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Subscribe Area Ends ***** --> --}}


@endsection
