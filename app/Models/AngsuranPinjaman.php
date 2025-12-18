<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model AngsuranPinjaman
 * 
 * Transaksi pembayaran angsuran pinjaman
 * Mencatat setiap pembayaran angsuran (pokok, jasa, denda)
 * 
 * Catatan:
 * - Saldo pinjaman TIDAK disimpan di database
 * - Semua saldo dihitung dari transaksi
 * - Total dibayar = pokok_dibayar + jasa_dibayar + denda_dibayar
 * - Status pinjaman diupdate otomatis ketika angsuran dibuat atau dihapus
 */
class AngsuranPinjaman extends Model
{
    protected $table = 'angsuran_pinjaman';

    protected $fillable = [
        'pinjaman_id',
        'tanggal_bayar',
        'angsuran_ke',
        'pokok_dibayar',
        'jasa_dibayar',
        'denda_dibayar',
        'total_dibayar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'angsuran_ke' => 'integer',
            'pokok_dibayar' => 'decimal:2',
            'jasa_dibayar' => 'decimal:2',
            'denda_dibayar' => 'decimal:2',
            'total_dibayar' => 'decimal:2',
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot(): void
    {
        parent::boot();

        // Auto-update status pinjaman dan buat transaksi kas masuk ketika angsuran dibuat
        static::created(function (AngsuranPinjaman $angsuran) {
            $angsuran->load('pinjaman.anggota');
            $pinjaman = $angsuran->pinjaman;
            
            // Update status pinjaman
            $pinjaman->updateStatusFromSisa();
            
            // Buat transaksi kas masuk otomatis
            TransaksiKas::create([
                'desa_id' => $pinjaman->desa_id,
                'tanggal_transaksi' => $angsuran->tanggal_bayar,
                'uraian' => "Pembayaran Angsuran ke-{$angsuran->angsuran_ke} - {$pinjaman->nomor_pinjaman} - {$pinjaman->anggota->nama}",
                'jenis_transaksi' => 'masuk',
                'jumlah' => $angsuran->total_dibayar,
                'angsuran_pinjaman_id' => $angsuran->id,
            ]);
        });

        // Auto-update status pinjaman ketika angsuran dihapus
        // Menggunakan deleting untuk menyimpan pinjaman_id sebelum dihapus
        static::deleting(function ($angsuran) {
            // Simpan pinjaman_id sebelum model dihapus
            $pinjamanId = $angsuran->pinjaman_id;
            
            // Setelah model dihapus, update status pinjaman
            static::deleted(function () use ($pinjamanId) {
                $pinjaman = Pinjaman::find($pinjamanId);
                if ($pinjaman) {
                    $pinjaman->updateStatusFromSisa();
                }
            });
        });
    }

    /**
     * Relasi ke pinjaman
     */
    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(Pinjaman::class);
    }
}
