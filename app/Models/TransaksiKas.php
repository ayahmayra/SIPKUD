<?php

namespace App\Models;

use App\Models\Concerns\HasDesaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiKas extends Model
{
    use HasDesaScope;

    protected $table = 'transaksi_kas';

    protected $fillable = [
        'desa_id',
        'tanggal_transaksi',
        'uraian',
        'jenis_transaksi',
        'jumlah',
        'pinjaman_id',
        'angsuran_pinjaman_id',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah' => 'decimal:2',
    ];

    /**
     * Relasi ke Desa
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Relasi ke Pinjaman (untuk kas keluar)
     */
    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(Pinjaman::class);
    }

    /**
     * Relasi ke Angsuran Pinjaman (untuk kas masuk)
     */
    public function angsuranPinjaman(): BelongsTo
    {
        return $this->belongsTo(AngsuranPinjaman::class);
    }
}
