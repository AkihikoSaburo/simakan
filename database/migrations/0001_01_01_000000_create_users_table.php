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
        Schema::create('bangsals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bangsal');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bangsal_id')->nullable()->constrained('bangsals')->nullOnDelete();
            $table->string('username')->unique();
            $table->enum('role', ['superadmin', 'admin', 'bangsal', 'dapur']);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('bangsals');
        Schema::dropIfExists('sessions');
    }
};
