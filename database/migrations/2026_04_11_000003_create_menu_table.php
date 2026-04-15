<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('idmenu');
            $table->unsignedInteger('idvendor');
            $table->string('nama_menu', 100);
            $table->integer('harga');
            $table->string('path_gambar')->nullable();
            $table->timestamps();

            $table->foreign('idvendor')->references('idvendor')->on('vendor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
