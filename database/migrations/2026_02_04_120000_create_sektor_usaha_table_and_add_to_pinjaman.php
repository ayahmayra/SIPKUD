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
        Schema::create('sektor_usaha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desa')->onDelete('cascade');
            $table->string('nama');
            $table->string('keterangan')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();

            $table->unique(['desa_id', 'nama']);
        });

        Schema::table('pinjaman', function (Blueprint $table) {
            $table->foreignId('sektor_usaha_id')->nullable()->after('anggota_id')->constrained('sektor_usaha')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropForeign(['sektor_usaha_id']);
        });
        Schema::dropIfExists('sektor_usaha');
    }
};
