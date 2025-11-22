<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi + relasi user, details, produk
        $transaksi = Transaction::with('details.produk', 'user')->get();

        // Kirim data ke view
        return view('transaksi.index', compact('transaksi'));
    }
}
