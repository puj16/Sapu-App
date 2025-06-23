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
        Schema::create('lahan', function (Blueprint $table) {
            $table->string('NOP', 18)->primary(); // NOP sebagai primary key, hanya angka
            $table->string('NIK', 16)->index(); // NIK sebagai index, hanya angka
            $table->decimal('Luas', 10, 3);            
            $table->enum('status', ['Milik', 'Sewa']); // Status lahan
            $table->string('Foto_SPPT')->nullable(); // Path atau URL foto SPPT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahan');
    }
};
