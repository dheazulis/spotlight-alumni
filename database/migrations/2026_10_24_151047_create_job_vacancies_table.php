<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nama_perusahaan');
            $table->string('posisi');
            $table->string('bidang_karier')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('deskripsi');
            $table->text('kualifikasi')->nullable();
            $table->date('tanggal_tutup')->nullable();
            $table->string('kontak_lamaran')->nullable();
            $table->text('tautan_lamaran')->nullable();

            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
            ])->default('menunggu');

            $table->foreignId('diproses_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('diproses_pada')->nullable();
            $table->text('catatan_admin')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};