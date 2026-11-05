<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobVacancy extends Model
{
    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'posisi',
        'bidang_karier',
        'lokasi',
        'deskripsi',
        'kualifikasi',
        'tanggal_tutup',
        'kontak_lamaran',
        'tautan_lamaran',
        'status',
        'diproses_oleh',
        'diproses_pada',
        'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tutup' => 'date',
            'diproses_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function adminPemroses(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'diproses_oleh'
        );
    }
}