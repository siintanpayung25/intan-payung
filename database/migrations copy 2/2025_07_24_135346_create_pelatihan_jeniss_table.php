<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanJenissTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_jeniss', function (Blueprint $table) {
            // Kolom Pelatihan Jenis
            $table->string('jenis_id', 2)->primary(); // ID Pelatihan Jenis
            $table->string('nama', 100); // Nama Pelatihan Jenis
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('jenis_id'); // Menambahkan index pada kolom 'jenis_id'

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelatihan_jeniss');
    }
}
