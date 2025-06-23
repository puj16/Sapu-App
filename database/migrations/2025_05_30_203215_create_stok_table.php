<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->string('periode', 50);
            $table->foreignId('id_pupuk')->constrained('pupuk')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('pupuk_datang');
            $table->integer('pupuk_tersisa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
