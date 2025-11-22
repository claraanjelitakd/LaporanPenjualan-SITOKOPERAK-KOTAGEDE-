<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi'; // karena bukan bentuk jamak default
    protected $fillable = ['transaksi_id', 'produk_id', 'jumlah', 'subtotal'];

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
