<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('petani', function (Blueprint $table) {
            $table->string('nik')->primary();
            $table->string('nama');
            $table->string('wa');
            $table->string('kk'); // Menyimpan path file KK
            $table->string('ktp'); // Menyimpan path file KTP
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('petani');
    }
};
