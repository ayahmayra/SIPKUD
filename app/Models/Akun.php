<?php

namespace App\Models;

use App\Models\Concerns\HasDesaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Akun
 * 
 * Master data untuk Chart of Accounts (COA)
 * Digunakan sebagai struktur dasar akuntansi
 * Akan digunakan oleh modul Jurnal dan Pelaporan di fase selanjutnya
 * 
 * Catatan: Modul-modul berikut akan dikembangkan di fase selanjutnya:
 * - Pinjaman
 * - Kas
 * - Jurnal (Akuntansi)
 * - Aset
 * - Pelaporan
 */
class Akun extends Model
{
    use HasDesaScope, SoftDeletes;

    protected $table = 'akun';

    protected $fillable = [
        'desa_id',
        'kode_akun',
        'nama_akun',
        'tipe_akun',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tipe_akun' => 'string',
            'status' => 'string',
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
     * Relasi ke user yang membuat
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang terakhir mengupdate
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope untuk filter aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk filter berdasarkan tipe akun
     */
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_akun', $tipe);
    }
}
