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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin_desa', 'executive_view'])->default('admin_desa')->after('email');
            $table->foreignId('kecamatan_id')->nullable()->after('role')->constrained('kecamatan')->onDelete('set null');
            $table->foreignId('desa_id')->nullable()->after('kecamatan_id')->constrained('desa')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['desa_id']);
            $table->dropColumn(['role', 'kecamatan_id', 'desa_id']);
        });
    }
};
