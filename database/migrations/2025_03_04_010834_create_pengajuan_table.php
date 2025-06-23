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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id('id_pengajuan'); // Primary key
            $table->string('NOP', 255); // Foreign key, sesuaikan dengan tipe data yang diinginkan
            $table->string('nik', 255); // Nomor Induk Kependudukan
            $table->decimal('luasan', 10, 3); // Luasan, dengan 2 angka desimal
            $table->text('catatan')->nullable(); // Catatan, bisa null
            $table->tinyInteger('status_validasi')->default(0);
            $table->year('tahun'); // Tahun
            $table->string('komoditi'); // Komoditi
            $table->foreign('NOP')->references('NOP')->on('lahan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nik')->references('nik')->on('lahan')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
