<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 100);
            $table->text('alamat')->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kodepos_kelurahan', 100)->nullable();
            $table->longText('foto_blob')->nullable();
            $table->string('foto_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
