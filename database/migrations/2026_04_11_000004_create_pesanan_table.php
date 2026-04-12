<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('idpesanan');

            $table->bigInteger('id')->unsigned();

            $table->integer('idguest')->unsigned()->nullable();

            $table->integer('total');

            $table->enum('status_bayar', ['pending', 'lunas', 'gagal'])->default('pending');
            $table->string('metode_bayar')->nullable();
            $table->string('midtrans_order_id', 100)->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_token')->nullable();

            $table->timestamps();

            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idguest')->references('idguest')->on('guest')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
