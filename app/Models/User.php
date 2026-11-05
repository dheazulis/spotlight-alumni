<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CareerJourney;
use App\Models\JobVacancy;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'foto_profil',
        'name',
        'email',
        'nim',
        'password',
        'role',
        'program_studi',
        'tahun_masuk',
        'tahun_lulus',
        'nomor_hp',
        'status_setelah_lulus',
        'pesan_motivasi',
        'tentang_saya',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function careerJourneys(): HasMany
    {
        return $this->hasMany(CareerJourney::class);
    }

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }
}
