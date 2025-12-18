<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum jenis_transaksi untuk menambah 'saldo_awal'
        DB::statement("ALTER TABLE transaksi_kas MODIFY COLUMN jenis_transaksi ENUM('masuk', 'keluar', 'saldo_awal') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum semula
        DB::statement("ALTER TABLE transaksi_kas MODIFY COLUMN jenis_transaksi ENUM('masuk', 'keluar') NOT NULL");
    }
};
