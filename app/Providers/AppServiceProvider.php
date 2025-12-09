<?php

namespace App\Providers;

use App\Models\Pengaturan;
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
