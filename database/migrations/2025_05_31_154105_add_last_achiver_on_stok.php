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
        Schema::table('stok', function (Blueprint $table) {
                $table->foreignId('last_achiever')
                    ->default(0)
                    ->after('pupuk_tersisa')
                    ->constrained('penyaluran')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('stok', function (Blueprint $table) {

            $table->dropColumn('last_achiever');

        });
    }
};
