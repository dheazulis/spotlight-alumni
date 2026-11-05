<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    protected $table = 'agendas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'gambar',
        'status',
        'admin_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}