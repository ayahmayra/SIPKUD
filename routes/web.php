<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Super Admin Routes - Master Data
    Route::middleware(['can:super_admin'])->group(function () {
        // Kecamatan CRUD
        Route::get('kecamatan', \App\Livewire\MasterData\Kecamatan\Index::class)->name('kecamatan.index');
        Route::get('kecamatan/create', \App\Livewire\MasterData\Kecamatan\Create::class)->name('kecamatan.create');
        Route::get('kecamatan/{kecamatan}/edit', \App\Livewire\MasterData\Kecamatan\Edit::class)->name('kecamatan.edit');
        
        // Desa CRUD
        Route::get('desa', \App\Livewire\MasterData\Desa\Index::class)->name('desa.index');
        Route::get('desa/create', \App\Livewire\MasterData\Desa\Create::class)->name('desa.create');
        Route::get('desa/{desa}/edit', \App\Livewire\MasterData\Desa\Edit::class)->name('desa.edit');
        
        // Pengaturan Sistem
        Route::get('pengaturan', \App\Livewire\MasterData\Pengaturan\Edit::class)->name('pengaturan.index');
    });

    // Super Admin & Admin Kecamatan Routes - Pengguna CRUD
    Route::middleware(['can:admin_kecamatan'])->group(function () {
        Route::get('pengguna', \App\Livewire\MasterData\Pengguna\Index::class)->name('pengguna.index');
        Route::get('pengguna/create', \App\Livewire\MasterData\Pengguna\Create::class)->name('pengguna.create');
        Route::get('pengguna/{user}/edit', \App\Livewire\MasterData\Pengguna\Edit::class)->name('pengguna.edit');
    });

    // Admin Desa & Admin Kecamatan Routes - Master Data
    // Index routes - bisa diakses oleh admin desa dan admin kecamatan (read-only untuk admin kecamatan)
    Route::middleware(['can:view_desa_data'])->group(function () {
        Route::get('kelompok', \App\Livewire\MasterData\Kelompok\Index::class)->name('kelompok.index');
        Route::get('anggota', \App\Livewire\MasterData\Anggota\Index::class)->name('anggota.index');
        Route::get('akun', \App\Livewire\MasterData\Akun\Index::class)->name('akun.index');
        Route::get('pinjaman', \App\Livewire\Pinjaman\Index::class)->name('pinjaman.index');
        Route::get('angsuran', \App\Livewire\Angsuran\Index::class)->name('angsuran.index');
        
        // Laporan
        Route::get('laporan/lpp-ued', \App\Livewire\Laporan\LppUed::class)->name('laporan.lpp-ued');
    });

    // Admin Desa Routes - Create & Edit (admin kecamatan tidak bisa)
    Route::middleware(['can:admin_desa'])->group(function () {
        // Kelompok CRUD
        Route::get('kelompok/create', \App\Livewire\MasterData\Kelompok\Create::class)->name('kelompok.create');
        Route::get('kelompok/{kelompok}/edit', \App\Livewire\MasterData\Kelompok\Edit::class)->name('kelompok.edit');
        
        // Anggota CRUD
        Route::get('anggota/create', \App\Livewire\MasterData\Anggota\Create::class)->name('anggota.create');
        Route::get('anggota/{anggota}/edit', \App\Livewire\MasterData\Anggota\Edit::class)->name('anggota.edit');
        
        // Akun CRUD
        Route::get('akun/create', \App\Livewire\MasterData\Akun\Create::class)->name('akun.create');
        Route::get('akun/{akun}/edit', \App\Livewire\MasterData\Akun\Edit::class)->name('akun.edit');
        
        // Pinjaman CRUD
        Route::get('pinjaman/create', \App\Livewire\Pinjaman\Create::class)->name('pinjaman.create');
        Route::get('pinjaman/{pinjaman}/edit', \App\Livewire\Pinjaman\Edit::class)->name('pinjaman.edit');
        
        // Angsuran CRUD
        Route::get('angsuran/create', \App\Livewire\Angsuran\Create::class)->name('angsuran.create');
    });
});
