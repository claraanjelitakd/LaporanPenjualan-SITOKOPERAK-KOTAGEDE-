<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukLike;

class ProdukLikeController extends Controller
{
    public function toggleLike(Request $request, $produkId)
    {
        // Cek produk valid
        $produk = Produk::find($produkId);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $ip = $request->ip();

        // cek apakah user (berdasarkan IP) sudah like sebelumnya
        $existing = ProdukLike::where('produk_id', $produkId)
            ->where('ip_address', $ip)
            ->first();

        if ($existing) {
            // UNLIKE
            $existing->delete();

            return response()->json([
                'success' => true,
                'liked' => false,
                'totalLikes' => ProdukLike::where('produk_id', $produkId)->count(),
                'message' => 'unliked'
            ]);
        }

        // LIKE baru
        ProdukLike::create([
            'produk_id' => $produkId,
            'ip_address' => $ip
        ]);

        return response()->json([
            'success' => true,
            'liked' => true,
            'totalLikes' => ProdukLike::where('produk_id', $produkId)->count(),
            'message' => 'liked'
        ]);
    }
}
