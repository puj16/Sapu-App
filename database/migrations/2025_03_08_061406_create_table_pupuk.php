<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pupuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pupuk', 100);
            $table->integer('harga'); // Ubah dari decimal ke integer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pupuk');
    }
};
