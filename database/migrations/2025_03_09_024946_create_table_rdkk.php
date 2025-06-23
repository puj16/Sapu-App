<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rdkk', function (Blueprint $table) {
            $table->id('id_RDKK');
            $table->year('tahun');
            $table->string('periode', 50);
            $table->string('komoditi', 100);
            $table->string('nik', 16); // Relasi ke tabel petani
            $table->foreign('nik')->references('nik')->on('petani')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_pengajuan'); // Relasi ke tabel petani
            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('id_pengajuan')->constrained('pengajuan')->onDelete('cascade')->onUpdate('cascade');;
            $table->foreignId('id_pupuk')->constrained('pupuk')->onDelete('cascade')->onUpdate('cascade');;
            $table->integer('volume_pupuk_mt1')->default(0);
            $table->integer('volume_pupuk_mt2')->default(0);
            $table->integer('volume_pupuk_mt3')->default(0);
            $table->integer('total_harga')->default(0); // Ubah dari decimal ke integer
            $table->tinyInteger('status_penyaluran')->default(0);
            $table->text('info')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rdkk');
    }
};
