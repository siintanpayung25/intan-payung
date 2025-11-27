<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanKategorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_kategoris', function (Blueprint $table) {
            // Kolom Pelatihan Kategori
            $table->string('kategori_id', 2)->primary(); // ID Pelatihan Kategori
            $table->string('nama', 50); // Nama Pelatihan Kategori
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('kategori_id'); // Menambahkan index pada kolom 'kategori_id'


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelatihan_kategoris');
    }
}
