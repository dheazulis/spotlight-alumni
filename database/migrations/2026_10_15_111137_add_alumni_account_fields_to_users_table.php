
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim', 30)->nullable()->unique();
            $table->boolean('must_change_password')->default(true);
            $table->timestamp('otp_verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nim']);
            $table->dropColumn([
                'nim',
                'must_change_password',
                'otp_verified_at',
            ]);
        });
    }
};
