<?php

namespace App\Providers;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define gates for role-based access
        Gate::define('super_admin', function ($user) {
            return $user->isSuperAdmin();
        });

        Gate::define('admin_kecamatan', function ($user) {
            return $user->isAdminKecamatan() || $user->isSuperAdmin();
        });

        Gate::define('admin_desa', function ($user) {
            return $user->isAdminDesa();
        });

        // Gate untuk read-only access - admin kecamatan bisa melihat data di kecamatannya
        Gate::define('view_desa_data', function ($user) {
            return $user->isAdminDesa() || $user->isAdminKecamatan() || $user->isSuperAdmin();
        });

        // Share pengaturan globally to all views
        View::composer('*', function ($view) {
            try {
                $pengaturan = Pengaturan::getSettings();
                $view->with('pengaturan', $pengaturan);
            } catch (\Exception $e) {
                // If table doesn't exist yet, use defaults
                $view->with('pengaturan', (object) [
                    'nama_instansi' => 'SIPKUD',
                    'nama_daerah' => 'Kabupaten',
                    'base_title' => 'SIPKUD - Sistem Informasi Pelaporan Keuangan USP Desa',
                    'logo_instansi' => null,
                    'favicon' => null,
                ]);
            }
        });
    }
}
