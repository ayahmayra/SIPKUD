<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatan = [
            [
                'nama_kecamatan' => 'Kecamatan Bengkalis',
                'kode_kecamatan' => 'KEC001',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Bantan',
                'kode_kecamatan' => 'KEC002',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Bukit Batu',
                'kode_kecamatan' => 'KEC003',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Mandau',
                'kode_kecamatan' => 'KEC004',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Rupat',
                'kode_kecamatan' => 'KEC005',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Rupat Utara',
                'kode_kecamatan' => 'KEC006',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Siak Kecil',
                'kode_kecamatan' => 'KEC007',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Pinggir',
                'kode_kecamatan' => 'KEC008',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Bandar Laksamana',
                'kode_kecamatan' => 'KEC009',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Talang Muandau',
                'kode_kecamatan' => 'KEC010',
                'status' => 'aktif',
            ],
            [
                'nama_kecamatan' => 'Kecamatan Bathin Solapan',
                'kode_kecamatan' => 'KEC011',
                'status' => 'aktif',
            ],
        ];

        foreach ($kecamatan as $data) {
            Kecamatan::firstOrCreate(
                ['kode_kecamatan' => $data['kode_kecamatan']],
                $data
            );
        }
    }
}
