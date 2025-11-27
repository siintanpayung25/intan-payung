<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanBentuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_bentuks', function (Blueprint $table) {
            // Kolom Pelatihan Bentuk
            $table->string('bentuk_id', 1)->primary(); // ID Pelatihan Bentuk
            $table->string('nama', 50); // Nama Pelatihan Bentuk
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('bentuk_id'); // Menambahkan index pada kolom 'bentuk_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelatihan_bentuks');
    }
}
