<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">

    <title>Toko Perak Kotagedhe</title>


    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo-hexashop.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl-carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="{{ route('guest-index') }}" class="logo">
                            <p style="color: black; font-size: 24px; font-weight: normal; text-transform: none; margin-top: 35px;">TekoPerakku</p>
                        </a>

                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="{{ route('guest-index') }}">Beranda</a></li>
                            <li class="submenu">
                                <a href="javascript:;">Kategori</a>
                                <ul>
                                    @foreach ($kategoris as $kategori)
                                        <li>
                                            <a href="{{ route('guest-productsByCategory', $kategori->slug) }}">
                                                {{ $kategori->nama_kategori_produk }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="scroll-to-section"><a href="{{ route('guest-about') }}">Tentang Kami</a></li>
                            <li class="scroll-to-section"><a href="{{ route('guest-contact') }}">Kontak</a></li>
                            <li class="scroll-to-section"><a href="{{ route('loginForm') }}">Login</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Content Start ***** -->
    <div class="content">
        @yield('content') <!-- Tempat untuk menampilkan content dinamis -->
    </div>
    <!-- ***** Content End ***** -->

    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="first-item">
                        <div class="logo">
                            <p style="color: black; font-size: 24px; font-weight: normal; text-transform: none; background-color: white; padding: 4px 8px;">TekoPerakku</p>
                        </div>
                        <ul>
                            <li><a href="#">59GX+957, JL. Watu Gateng, Prenggan, Kec. Kotagede, Kota Yogyakarta,
                                    Daerah Istimewa Yogyakarta 55172</a>
                            </li>
                            <li><a href="#">kotagedhe@gmail.com</a></li>
                            <li><a href="#">088-098-202</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h4>Belanja &amp; Kategori</h4>
                    <ul>
                        @foreach ($randomKategoris as $kategori)
                            <li>
                                <a href="{{ route('guest-productsByCategory', $kategori->slug) }}">
                                    {{ $kategori->nama_kategori_produk }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>Informasi Kami</h4>
                    <ul>
                        <li><a href="{{ route('guest-index') }}">Beranda</a></li>
                        <li><a href="{{ route('guest-about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('guest-contact') }}">Kontak Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>Bantuan &amp; Pertanyaan</h4>
                    <ul>
                        <li><a href="#">Bantuan</a></li>
                        <li><a href="#">Pertanyaan</a></li>
                    </ul>
                </div>
                <div class="col-lg-12">
                    <div class="under-footer">
                        <p>Copyright © 2025 Toko Perak Kotagedhe. All rights reserved.</p>
                        </p>
                        <ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-2.1.0.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/popper.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/js/owl-carousel.js') }}"></script>
    <script src="{{ asset('assets/js/accordions.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/imgfix.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick.js') }}"></script>
    <script src="{{ asset('assets/js/lightbox.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.js') }}"></script>

    <!-- Global Init -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        $(function() {
            var selectedClass = "";
            $("p").click(function() {
                selectedClass = $(this).attr("data-rel");
                $("#portfolio").fadeTo(50, 0.1);
                $("#portfolio div").not("." + selectedClass).fadeOut();
                setTimeout(function() {
                    $("." + selectedClass).fadeIn();
                    $("#portfolio").fadeTo(50, 1);
                }, 500);
            });
        });
    </script>

</body>

</html>
