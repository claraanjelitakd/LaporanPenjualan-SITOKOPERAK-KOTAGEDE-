<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukView;

class ProdukViewController extends Controller
{
    public function store(Request $request, $produkId)
    {
        // Pastikan produk ada
        $produk = Produk::find($produkId);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $sessionKey = 'viewed_produk_' . $produkId;

        // Cegah view ganda dalam 1 sesi
        if (!session()->has($sessionKey)) {

            ProdukView::create([
                'produk_id' => $produkId,
                'ip_address' => $request->ip(),
            ]);

            session()->put($sessionKey, true);
        }

        return response()->json([
            'success' => true,
            'message' => 'view recorded',
            'totalViews' => ProdukView::where('produk_id', $produkId)->count()
        ]);
    }
}
