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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->boolean('nasi')->default(false);
            $table->boolean('bubur')->default(false);
            $table->boolean('makanan_cair')->default(false);
            $table->boolean('bs')->default(false);
            $table->boolean('sonde')->default(false);
            $table->string('diet_pasien')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
