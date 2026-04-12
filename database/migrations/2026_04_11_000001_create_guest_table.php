<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest', function (Blueprint $table) {
            $table->increments('idguest'); // PK manual style
            $table->string('nama_guest', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest');
    }
};
