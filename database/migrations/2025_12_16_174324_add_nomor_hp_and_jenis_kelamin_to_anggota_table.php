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
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('nomor_hp', 20)->nullable()->after('alamat');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('nomor_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn(['nomor_hp', 'jenis_kelamin']);
        });
    }
};
