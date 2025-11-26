@extends('guest.layouts.main')
@section('title', 'Berbagai Macam Produk')
@section('content')

<div class="page-heading" id="top">
    <div class="container">
        <div class="inner-content">
            <h2>Temukan Produk Favoritmu!</h2>
            <span>Pilihan lengkap & harga terbaik hanya di toko kami</span>
        </div>
    </div>
</div>

<section class="section" id="products">
    <div class="container">
        <div class="section-heading">
            <h2>Produk Terbaru Kami!</h2>
            <span>Temukan produk yang kamu suka!</span>
        </div>
    </div>

    <div class="container">
        <div class="row">

            @foreach ($produks as $produk)
                <div class="col-lg-4 mb-4">
                    <div class="item">

                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li>
                                        <a href="{{ route('guest-singleProduct', $produk->slug) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </li>

                                    {{-- LIKE --}}
                                    <li>
                                        <button type="button"
                                                class="like-btn"
                                                data-id="{{ $produk->id }}">
                                            <i class="fa fa-star star-icon {{ $produk->likes_count > 0 ? 'active' : '' }}"></i>
                                        </button>
                                    </li>

                                    <li>
                                        <button type="button"><i class="fa fa-shopping-cart"></i></button>
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ route('guest-singleProduct', $produk->slug) }}" class="img-link">
                                <img src="{{ asset('storage/' . (optional($produk->fotoProduk->first())->file_foto_produk ?? 'placeholder.jpg')) }}"
                                     alt="{{ $produk->nama_produk }}">
                            </a>
                        </div>

                        <div class="down-content">
                            <h4><a href="{{ route('guest-singleProduct', $produk->slug) }}">{{ $produk->nama_produk }}</a></h4>
                            <span>Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            <p>{{ $produk->deskripsi }}</p>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    </div>

</section>
@endsection

{{-- ========== CSS ========== --}}
<style>
    .thumb { position: relative; overflow: hidden; border-radius: 6px; }
    .star-icon { transition: .2s ease; color: white; }
    .star-icon.active { color: #ffc107 !important; }
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
