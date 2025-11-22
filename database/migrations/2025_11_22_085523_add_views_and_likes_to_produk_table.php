<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table untuk menyimpan logs view produk (1 IP hanya 1x dihitung)
        Schema::create('produk_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // Table untuk menyimpan likes per user
        Schema::create('produk_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['produk_id', 'user_id']); // user cuma boleh like sekali
        });

        // NOTE: Tidak menambahkan kolom views/likes di tabel produk.
        // Perhitungan dilakukan saat runtime menggunakan COUNT pada tabel di atas.
    }

    public function down()
    {
        Schema::dropIfExists('produk_views');
        Schema::dropIfExists('produk_likes');
    }
};
