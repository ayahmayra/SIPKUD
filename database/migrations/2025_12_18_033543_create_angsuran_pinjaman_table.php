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
        Schema::create('angsuran_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->onDelete('restrict');
            $table->date('tanggal_bayar');
            $table->integer('angsuran_ke');
            $table->decimal('pokok_dibayar', 15, 2);
            $table->decimal('jasa_dibayar', 15, 2);
            $table->decimal('denda_dibayar', 15, 2)->default(0);
            $table->decimal('total_dibayar', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran_pinjaman');
    }
};
