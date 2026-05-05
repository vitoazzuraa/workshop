<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->increments('idvendor');
            $table->string('nama_vendor', 255);
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->increments('idmenu');
            $table->string('nama_menu', 255);
            $table->integer('harga');
            $table->string('path_gambar', 255)->nullable();
            $table->unsignedInteger('idvendor');
            $table->foreign('idvendor')->references('idvendor')->on('vendor')->onDelete('cascade');
        });

        // Guest = customer kantin tanpa login, ID format guest_000001
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 15)->unique()->comment('guest_000001 format');
            $table->string('nama', 100);
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('idpesanan');
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->string('nama', 255);
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total')->default(0);
            $table->tinyInteger('metode_bayar')->default(0)->comment('1=virtual_account,2=qris');
            $table->smallInteger('status_bayar')->default(0)->comment('0=pending,1=lunas');
            $table->string('midtrans_order_id', 100)->nullable();
            $table->text('midtrans_snap_token')->nullable();
            $table->foreign('guest_id')->references('id')->on('guests')->nullOnDelete();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->increments('iddetail_pesanan');
            $table->unsignedInteger('idmenu');
            $table->unsignedInteger('idpesanan');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('catatan', 255)->nullable();
            $table->foreign('idmenu')->references('idmenu')->on('menu')->onDelete('cascade');
            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('vendor');
    }
};
