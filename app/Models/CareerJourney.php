<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerJourney extends Model
{
    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'jabatan',
        'bidang_karier',
        'tanggal_mulai',
        'tanggal_selesai',
        'masih_bekerja',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'masih_bekerja' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
