<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\DetailTransaksi;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kode_produk',
        'kategori_produk_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'slug'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($produk) {
            $slug = Str::slug($produk->nama_produk);
            $existingSlugCount = self::where('slug', $slug)->count();
            if ($existingSlugCount > 0) {
                $slug .= '-' . ($existingSlugCount + 1);
            }
            $produk->slug = $slug;
        });

        static::updating(function ($produk) {
            $produk->slug = Str::slug($produk->nama_produk);
        });
    }

    // Relasi kategori
    public function kategoriProduk()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }

    // Relasi foto
    public function fotoProduk()
    {
        return $this->hasMany(FotoProduk::class, 'produk_id');
    }

    // Relasi ke transaksi (detail)
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }

    // Relasi usaha
    public function usaha()
    {
        return $this->belongsToMany(Usaha::class, 'usaha_produk', 'produk_id', 'usaha_id');
    }

    // Relasi ke view
    public function views()
    {
        return $this->hasMany(ProdukView::class, 'produk_id');
    }

    // ⭐ FIX: Relasi ke likes (WAJIB NAMA likes)
    public function likes()
{
    return $this->hasMany(ProdukLike::class, 'produk_id');
}
}
