<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukView;

class ProdukViewController extends Controller
{
    public function store(Request $request, $produkId)
    {
        $sessionId = session()->getId();

        $view = ProdukView::firstOrCreate(
            [
                'produk_id' => $produkId,
                'session_id' => $sessionId,
            ],
            ['total_klik' => 0]
        );

        $view->increment('total_klik');

        return response()->json([
            'success' => true
        ]);
    }
}
