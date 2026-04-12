<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->increments('idpesanan_detail');
            $table->integer('idpesanan')->unsigned();
            $table->integer('idmenu')->unsigned();
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('idmenu')->references('idmenu')->on('menu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_detail');
    }
};
