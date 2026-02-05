<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Jalankan: php artisan migrate:fresh --seed
     * Atau:     php artisan db:seed
     *
     * Urutan (data user selalu dijalankan):
     * 1. Kecamatan → Desa → User (wajib, termasuk Super Admin & Admin Desa)
     * 2. GlobalCoa → UnitUsaha → SektorUsaha (master)
     * 3. TestingData (Kelapapati: user admin, kelompok, anggota, transaksi, jurnal)
     * 4. KelapapatiFaker (dummy faker: anggota, transaksi kas, pinjaman+sektor)
     * 5. PinjamanAngsuran (pinjaman & angsuran Kelapapati)
     * 6. Pengumuman
     */
    public function run(): void
    {
        $this->call([
            KecamatanSeeder::class,
            DesaSeeder::class,
            UserSeeder::class,           // Data user (Super Admin, Admin Kecamatan, Admin Desa, dll)
            GlobalCoaSeeder::class,
            UnitUsahaSeeder::class,
            SektorUsahaSeeder::class,   // Sektor usaha untuk pinjaman (struktur baru)
            TestingDataSeeder::class,
            KelapapatiFakerSeeder::class,
            PinjamanAngsuranSeeder::class,
            PengumumanSeeder::class,
        ]);
    }
}
