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
        Schema::create('detail_penyaluran_sebagian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penyaluran')
                ->constrained('penyaluran')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('id_pupuk')
                ->constrained('pupuk')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('volume_pupuk')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penyaluran_sebagian');
    }
};
