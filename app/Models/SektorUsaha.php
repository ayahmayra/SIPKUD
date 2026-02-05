<?php

namespace App\Models;

use App\Models\Concerns\HasDesaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Sektor Usaha
 *
 * Master data sektor usaha untuk pinjaman (contoh: Pertanian, Perdagangan, Peternakan).
 * Dikelola per desa oleh Admin Desa.
 */
class SektorUsaha extends Model
{
    use HasDesaScope;

    protected $table = 'sektor_usaha';

    protected $fillable = [
        'desa_id',
        'nama',
        'keterangan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function pinjaman(): HasMany
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
