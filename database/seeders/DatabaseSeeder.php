<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Jalankan dengan: php artisan migrate:fresh --seed
     * Urutan: master → user → COA → unit usaha → data Kelapapati (testing + faker) → pinjaman/angsuran → pengumuman.
     */
    public function run(): void
    {
        $this->call([
            KecamatanSeeder::class,
            DesaSeeder::class,
            UserSeeder::class,
            GlobalCoaSeeder::class,
            UnitUsahaSeeder::class,
            TestingDataSeeder::class,
            KelapapatiFakerSeeder::class,
            PinjamanAngsuranSeeder::class,
            PengumumanSeeder::class,
        ]);
    }
}
