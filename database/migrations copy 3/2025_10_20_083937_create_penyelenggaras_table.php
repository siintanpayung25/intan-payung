<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penyelenggaras', function (Blueprint $table) {
            // Kolom ini akan menjadi PK dan FK di tabel lain
            $table->string('penyelenggara_id', 4)->primary();
            $table->string('nama', 255);
            $table->string('tipe', 20); // 'Instansi' atau 'Universitas'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penyelenggaras');
    }
};
