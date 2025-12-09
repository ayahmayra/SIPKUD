<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait untuk menambahkan scope desa_id pada model tenant
 * 
 * Scope ini akan otomatis memfilter data berdasarkan desa_id user yang sedang login.
 * Super Admin dapat mengakses semua data tanpa filter.
 * 
 * Catatan: Modul-modul berikut akan dikembangkan di fase selanjutnya:
 * - Pinjaman
 * - Kas
 * - Jurnal (Akuntansi)
 * - Aset
 * - Pelaporan
 */
trait HasDesaScope
{
    /**
     * Boot the scope
     */
    protected static function bootHasDesaScope(): void
    {
        static::addGlobalScope('desa', function (Builder $builder) {
            // Skip scope jika tidak ada user yang login (untuk seeder, artisan commands, dll)
            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();
            
            // Super Admin dapat mengakses semua data
            if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return;
            }

            // Filter berdasarkan desa_id user yang sedang login
            if ($user && $user->desa_id) {
                $builder->where('desa_id', $user->desa_id);
            }
        });
    }
}

