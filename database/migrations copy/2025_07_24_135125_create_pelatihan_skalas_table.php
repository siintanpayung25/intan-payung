<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanSkalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_skalas', function (Blueprint $table) {
            // Kolom Pelatihan Skala
            $table->string('skala_id', 1)->primary(); // ID Pelatihan Skala
            $table->string('nama', 50); // Nama Pelatihan Skala
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('skala_id'); // Menambahkan index pada kolom 'skala_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelatihan_skalas');
    }
}
