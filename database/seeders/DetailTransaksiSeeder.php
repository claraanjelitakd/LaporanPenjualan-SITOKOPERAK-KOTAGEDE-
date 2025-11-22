<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $transaksi_ids = DB::table('transaksi')->pluck('id')->toArray();
        $produk_ids = DB::table('produk')->pluck('id')->toArray();

        foreach ($transaksi_ids as $transaksi_id) {

            // jumlah jenis produk dalam setiap transaksi (1–5 produk per transaksi)
            $jumlah_produk_transaksi = rand(1, min(5, count($produk_ids)));

            // pilih produk random tanpa duplikat
            $produk_random = (array) array_rand(array_flip($produk_ids), $jumlah_produk_transaksi);

            $total = 0;

            foreach ($produk_random as $produk_id) {

                // ambil harga produk
                $harga = DB::table('produk')->where('id', $produk_id)->value('harga');

                // probabilitas demand
                $chance = rand(1, 100);

                if ($chance <= 20) {
                    // 20% chance → produk best seller (jumlah tinggi)
                    $jumlah = rand(3, 7);
                } elseif ($chance <= 60) {
                    // 40% chance → produk normal
                    $jumlah = rand(2, 4);
                } else {
                    // 40% chance → slow moving
                    $jumlah = 1;
                }

                // hitung subtotal
                $subtotal = $harga * $jumlah;

                // insert detail transaksi
                DB::table('detail_transaksi')->insert([
                    'transaksi_id' => $transaksi_id,
                    'produk_id' => $produk_id,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                    'created_at' => now()->subDays(rand(1, 60)), // bikin data variatif per waktu
                    'updated_at' => now(),
                ]);

                $total += $subtotal;
            }

            // update total transaksi setelah semua line dimasukkan
            DB::table('transaksi')
                ->where('id', $transaksi_id)
                ->update([
                    'total' => $total,
                    'updated_at' => now(),
                ]);
        }
    }
}
