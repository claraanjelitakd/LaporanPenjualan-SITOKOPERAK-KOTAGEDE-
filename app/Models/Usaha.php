<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usaha extends Model
{
    protected $table = 'usaha';
    protected $fillable = [
        'kode_usaha',
        'nama_usaha',
        'telp_usaha',
        'email_usaha',
        'deskripsi_usaha',
        'foto_usaha',
        'link_gmap_usaha',
        'status_usaha',
    ];

    public function usahaJenis()
    {
        return $this->hasMany(UsahaJenis::class);
    }

    public function usahaPengerajin()
    {
        return $this->hasMany(UsahaPengerajin::class);
    }

    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'usaha_produk', 'usaha_id', 'produk_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaction::class, 'usaha_id');
    }
}
