<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists before renaming
        if (Schema::hasColumn('users', 'name')) {
            // Use raw SQL for MySQL/MariaDB column rename
            DB::statement('ALTER TABLE `users` CHANGE `name` `nama` VARCHAR(255) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if column exists before renaming back
        if (Schema::hasColumn('users', 'nama')) {
            // Use raw SQL for MySQL/MariaDB column rename
            DB::statement('ALTER TABLE `users` CHANGE `nama` `name` VARCHAR(255) NOT NULL');
        }
    }
};
