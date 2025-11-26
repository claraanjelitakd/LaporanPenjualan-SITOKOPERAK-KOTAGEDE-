<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\FotoProduk;
use App\Models\ProdukView;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategoriProduk', 'fotoProduk')->get();
        return view('guest.pages.index', [
            'produks' => $produks,
        ]);
    }

    public function productsByCategory($slug)
    {
        $kategori = KategoriProduk::where('slug', $slug)->firstOrFail();

        $produks = Produk::where('kategori_produk_id', $kategori->id)
            ->with('fotoProduk')
            ->withCount(['likes as likes_count'])
            ->get();

        return view('guest.pages.products', [
            'kategori' => $kategori,
            'produks' => $produks,
        ]);
    }


    public function singleProduct($slug)
    {
        $produk = Produk::where('slug', $slug)->firstOrFail();
        $view = ProdukView::firstOrCreate(
            [
                'produk_id' => $produk->id,
                'session_id' => session()->getId(),
            ],
            ['total_klik' => 0]
        );

        $view->increment('total_klik');

        return view('guest.pages.single-product', [
            'produk' => $produk,
        ]);
    }

    public function about()
    {
        return view('guest.pages.about');
    }
    public function contact()
    {
        return view('guest.pages.contact');
    }
}
