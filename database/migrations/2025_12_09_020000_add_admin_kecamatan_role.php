<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include admin_kecamatan
        // Note: MySQL doesn't support ALTER ENUM directly, so we need to use raw SQL
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin_kecamatan', 'admin_desa', 'executive_view') NOT NULL DEFAULT 'admin_desa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin_desa', 'executive_view') NOT NULL DEFAULT 'admin_desa'");
    }
};
