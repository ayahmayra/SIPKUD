<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'prioritas',
        'tipe',
        'aktif',
        'tanggal_mulai',
        'tanggal_selesai',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    /**
     * Relasi ke user yang membuat
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk pengumuman aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
