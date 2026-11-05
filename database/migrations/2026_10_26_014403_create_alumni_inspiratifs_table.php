<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_inspiratifs', function (Blueprint $table) {
            $table->id();

            // Alumni yang diajukan
            $table->foreignId('alumni_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Kaprodi yang mengajukan
            $table->foreignId('pengaju_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Periode publikasi
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');

            // Status pengajuan
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
            ])->default('menunggu');

            // Admin yang memutuskan dan catatan
            $table->foreignId('peninjau_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('catatan_admin')->nullable();
            $table->timestamp('diputuskan_pada')->nullable();

            $table->timestamps();

            $table->index(['status', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_inspiratifs');
    }
};
