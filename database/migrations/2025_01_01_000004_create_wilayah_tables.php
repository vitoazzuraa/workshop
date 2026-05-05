<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_provinsi', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama', 100);
        });

        Schema::create('wilayah_kota', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('id_provinsi', 10);
            $table->string('nama', 100);
            $table->foreign('id_provinsi')->references('id')->on('wilayah_provinsi')->onDelete('cascade');
        });

        Schema::create('wilayah_kecamatan', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('id_kota', 10);
            $table->string('nama', 100);
            $table->foreign('id_kota')->references('id')->on('wilayah_kota')->onDelete('cascade');
        });

        Schema::create('wilayah_kelurahan', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('id_kecamatan', 10);
            $table->string('nama', 100);
            $table->foreign('id_kecamatan')->references('id')->on('wilayah_kecamatan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_kelurahan');
        Schema::dropIfExists('wilayah_kecamatan');
        Schema::dropIfExists('wilayah_kota');
        Schema::dropIfExists('wilayah_provinsi');
    }
};
