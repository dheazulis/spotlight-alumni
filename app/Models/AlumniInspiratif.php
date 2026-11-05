<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniInspiratif extends Model
{
    protected $table = 'alumni_inspiratifs';

    protected $fillable = [
        'alumni_id',
        'pengaju_id',
        'bulan',
        'tahun',
        'status',
        'peninjau_id',
        'catatan_admin',
        'diputuskan_pada',
    ];

    protected function casts(): array
    {
        return [
            'bulan' => 'integer',
            'tahun' => 'integer',
            'diputuskan_pada' => 'datetime',
        ];
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function peninjau(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peninjau_id');
    }
}
