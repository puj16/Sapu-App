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
        //
        Schema::table('penyaluran', function (Blueprint $table) {
            //
            $table->timestamps(); // Menambahkan kolom timestamp setelah status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('penyaluran', function (Blueprint $table) {
            $table->dropTimestamps();
        });

    }
};
