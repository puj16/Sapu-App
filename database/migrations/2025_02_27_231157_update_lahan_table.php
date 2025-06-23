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
        Schema::table('lahan', function (Blueprint $table) {
            //
            $table->enum('status', ['Milik', 'Sewa', 'Garapan', 'Bagi Hasil'])->change();
            $table->string('add_by')->after('status'); // Menambahkan kolom add_by setelah status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lahan', function (Blueprint $table) {
            $table->enum('status', ['Milik', 'Sewa'])->change(); // Kembalikan ke kondisi awal
            $table->dropColumn('add_by');
        });
    }
};
