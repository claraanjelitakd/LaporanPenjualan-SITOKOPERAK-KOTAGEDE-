<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ubah kolom role supaya support admin, guest, customer
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'guest', 'customer') DEFAULT 'guest'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // balikin lagi kalau rollback (cuma admin & guest)
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'guest') DEFAULT 'guest'");
    }
};
