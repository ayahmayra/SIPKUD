<?php

namespace Database\Seeders;

use App\Models\Akun;
use Illuminate\Database\Seeder;

/**
 * Seeder Chart of Accounts (COA) Global.
 * Master akun digunakan oleh seluruh desa.
 */
class GlobalCoaSeeder extends Seeder
{
    public function run(): void
    {
        $chartOfAccounts = [
            // ASET - Kas/Bank (akun kas untuk transaksi)
            ['kode_akun' => '1-1000', 'nama_akun' => 'Kas', 'tipe_akun' => 'aset'],
            ['kode_akun' => '1-1010', 'nama_akun' => 'Bank', 'tipe_akun' => 'aset'],
            ['kode_akun' => '1-1020', 'nama_akun' => 'Kas Kecil', 'tipe_akun' => 'aset'],
            ['kode_akun' => '1-1100', 'nama_akun' => 'Piutang Pinjaman Anggota', 'tipe_akun' => 'aset'],
            ['kode_akun' => '1-2000', 'nama_akun' => 'Akumulasi Penyusutan Aset', 'tipe_akun' => 'aset'],
            // KEWAJIBAN
            ['kode_akun' => '2-1000', 'nama_akun' => 'Hutang Usaha', 'tipe_akun' => 'kewajiban'],
            ['kode_akun' => '2-2000', 'nama_akun' => 'Hutang Bank', 'tipe_akun' => 'kewajiban'],
            // EKUITAS
            ['kode_akun' => '3-1000', 'nama_akun' => 'Modal', 'tipe_akun' => 'ekuitas'],
            ['kode_akun' => '3-2000', 'nama_akun' => 'Laba Ditahan', 'tipe_akun' => 'ekuitas'],
            // PENDAPATAN
            ['kode_akun' => '4-1000', 'nama_akun' => 'Pendapatan Simpanan', 'tipe_akun' => 'pendapatan'],
            ['kode_akun' => '4-2000', 'nama_akun' => 'Pendapatan Jasa Pinjaman', 'tipe_akun' => 'pendapatan'],
            // BEBAN
            ['kode_akun' => '5-1000', 'nama_akun' => 'Beban Operasional', 'tipe_akun' => 'beban'],
            ['kode_akun' => '5-2000', 'nama_akun' => 'Beban Gaji', 'tipe_akun' => 'beban'],
            ['kode_akun' => '5-3000', 'nama_akun' => 'Beban Administrasi', 'tipe_akun' => 'beban'],
            ['kode_akun' => '5-4000', 'nama_akun' => 'Beban Penyusutan', 'tipe_akun' => 'beban'],
        ];

        foreach ($chartOfAccounts as $akun) {
            Akun::firstOrCreate(
                ['kode_akun' => $akun['kode_akun']],
                [
                    'nama_akun' => $akun['nama_akun'],
                    'tipe_akun' => $akun['tipe_akun'],
                    'status' => 'aktif',
                ]
            );
        }

        $this->command->info('✓ COA Global berhasil dibuat');
    }
}
