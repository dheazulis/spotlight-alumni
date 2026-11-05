<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('program_studi')->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('status_setelah_lulus')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'program_studi',
                'tahun_masuk',
                'tahun_lulus',
                'nomor_hp',
                'status_setelah_lulus',
            ]);

        });
    }
};
