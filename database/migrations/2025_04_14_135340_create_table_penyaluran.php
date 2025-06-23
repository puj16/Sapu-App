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
        Schema::create('penyaluran', function (Blueprint $table) {
            $table->id(); // id*
            $table->string('nik', 16); // Relasi ke tabel petani
            $table->foreign('nik')->references('nik')->on('petani')->onDelete('cascade')->onUpdate('cascade');
            $table->year('tahun');
            $table->string('periode'); // misal: 'Ganjil', 'Genap', atau lainnya
            $table->string('komoditi');
            $table->date('tgl_penyaluran')->nullable();
            $table->date('tgl_bayar')->nullable();
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->string('metode_bayar')->nullable();   
            $table->tinyInteger('status_pembayaran')->default(0);          
            $table->tinyInteger('status_penyaluran')->default(0);
            $table->text('info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyaluran');
    }
};
