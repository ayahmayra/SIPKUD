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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desa')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('restrict');
            $table->string('nomor_pinjaman')->unique();
            $table->date('tanggal_pinjaman');
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->integer('jangka_waktu_bulan');
            $table->decimal('jasa_persen', 5, 2);
            $table->enum('status_pinjaman', ['aktif', 'lunas'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
