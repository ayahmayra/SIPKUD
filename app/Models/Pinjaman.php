<?php

namespace App\Models;

use App\Models\Concerns\HasDesaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Pinjaman
 * 
 * Transaksi pinjaman anggota USP/UED-SP
 * Merupakan transaksi awal sistem simpan pinjam
 * 
 * Catatan:
 * - Saldo pinjaman TIDAK disimpan di database
 * - Semua saldo dihitung dari transaksi
 * - LPP UED adalah LAPORAN, bukan input
 */
class Pinjaman extends Model
{
    use HasDesaScope;

    protected $table = 'pinjaman';

    protected $fillable = [
        'desa_id',
        'anggota_id',
        'nomor_pinjaman',
        'tanggal_pinjaman',
        'jumlah_pinjaman',
        'jangka_waktu_bulan',
        'jasa_persen',
        'status_pinjaman',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjaman' => 'date',
            'jumlah_pinjaman' => 'decimal:2',
            'jangka_waktu_bulan' => 'integer',
            'jasa_persen' => 'decimal:2',
            'status_pinjaman' => 'string',
        ];
    }

    /**
     * Relasi ke desa
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Relasi ke anggota
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    /**
     * Scope untuk filter aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_pinjaman', 'aktif');
    }

    /**
     * Scope untuk filter lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status_pinjaman', 'lunas');
    }
}
