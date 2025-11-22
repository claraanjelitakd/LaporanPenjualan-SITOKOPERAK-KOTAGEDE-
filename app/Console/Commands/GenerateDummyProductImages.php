<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Support\Facades\Storage;
use App\Models\Produk;
use App\Models\FotoProduk;

#[AsCommand(name: 'generate:dummy-images')]
class GenerateDummyProductImages extends Command
{
    protected $description = 'Generate dummy product images for each product';

    public function handle()
    {
        $products = Produk::all();

        if ($products->count() === 0) {
            $this->error("❌ Tidak ada produk");
            return;
        }

        foreach ($products as $product) {

            if ($product->fotoProduk()->exists()) {
                $this->warn("➡️ Produk {$product->nama_produk} sudah punya foto, skip");
                continue;
            }

            $image = file_get_contents("https://picsum.photos/600/600?random=" . rand(1000,9999));
            $filename = "produk_" . uniqid() . ".jpg";
            Storage::disk('public')->put("foto_produk/" . $filename, $image);
            // Generate kode foto otomatis
            $lastFoto = FotoProduk::orderBy('id', 'desc')->first();
            $nextNumber = $lastFoto ? ((int)substr($lastFoto->kode_foto_produk, 2) + 1) : 1;
            $kodeFoto = 'FP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            FotoProduk::create([
                'produk_id' => $product->id,
                'file_foto_produk' => "foto_produk/" . $filename,
                'kode_foto_produk' => $kodeFoto
            ]);

            $this->info("✔️ Foto dibuat untuk {$product->nama_produk}");
        }

        $this->info("\n🎉 Semua foto dummy berhasil dibuat!");
    }
}
