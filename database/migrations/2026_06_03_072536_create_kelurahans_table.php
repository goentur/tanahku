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
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('header_id')->nullable();
            $table->string('kd_wilayah')->nullable();
            $table->string('kd_propinsi')->nullable();
            $table->string('kd_dati2')->nullable();
            $table->string('kd_kecamatan')->nullable();
            $table->string('kd_kelurahan')->nullable();
            $table->string('nama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahans');
    }
};
