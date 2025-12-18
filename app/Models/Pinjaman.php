<?php

namespace App\Models;

use App\Models\Concerns\HasDesaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Relasi ke angsuran
     */
    public function angsuran(): HasMany
    {
        return $this->hasMany(AngsuranPinjaman::class);
    }

    /**
     * Hitung total pokok yang sudah dibayar
     * Dihitung dari transaksi angsuran, bukan dari database
     */
    public function getTotalPokokDibayarAttribute(): float
    {
        return (float) $this->angsuran()->sum('pokok_dibayar');
    }

    /**
     * Hitung sisa pinjaman
     * Sisa = jumlah_pinjaman - total pokok yang sudah dibayar
     */
    public function getSisaPinjamanAttribute(): float
    {
        $totalPokokDibayar = (float) $this->angsuran()->sum('pokok_dibayar');
        return max(0, (float) $this->jumlah_pinjaman - $totalPokokDibayar);
    }

    /**
     * Hitung status pinjaman berdasarkan sisa pinjaman
     * AKTIF jika sisa_pinjaman > 0
     * LUNAS jika sisa_pinjaman = 0
     * Dihitung dari transaksi, bukan dari database
     */
    public function getStatusPinjamanCalculatedAttribute(): string
    {
        $sisaPinjaman = $this->sisa_pinjaman;
        return $sisaPinjaman > 0 ? 'aktif' : 'lunas';
    }

    /**
     * Update status pinjaman berdasarkan sisa pinjaman
     * Dipanggil otomatis ketika angsuran dibuat atau dihapus
     */
    public function updateStatusFromSisa(): void
    {
        $newStatus = $this->status_pinjaman_calculated;
        
        // Hanya update jika status berbeda
        if ($this->status_pinjaman !== $newStatus) {
            $this->update(['status_pinjaman' => $newStatus]);
        }
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
