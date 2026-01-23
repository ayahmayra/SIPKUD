<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\UnitUsaha;
use Illuminate\Database\Seeder;

/**
 * Seeder untuk Unit Usaha standar BUM Desa
 */
class UnitUsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allDesa = Desa::all();

        // Unit usaha standar
        $unitUsahaStandar = [
            [
                'kode_unit' => 'USP',
                'nama_unit' => 'Unit Simpan Pinjam',
                'deskripsi' => 'Unit usaha simpan pinjam untuk anggota',
                'status' => 'aktif',
            ],
            [
                'kode_unit' => 'UMUM',
                'nama_unit' => 'Unit Usaha Umum',
                'deskripsi' => 'Unit usaha umum BUM Desa',
                'status' => 'aktif',
            ],
        ];

        foreach ($allDesa as $desa) {
            foreach ($unitUsahaStandar as $unit) {
                UnitUsaha::firstOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'kode_unit' => $unit['kode_unit'],
                    ],
                    [
                        'nama_unit' => $unit['nama_unit'],
                        'deskripsi' => $unit['deskripsi'],
                        'status' => $unit['status'],
                    ]
                );
            }
        }

        $this->command->info('✓ Unit Usaha standar berhasil dibuat untuk semua desa.');
    }
}
