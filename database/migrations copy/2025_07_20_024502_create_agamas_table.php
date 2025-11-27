<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgamasTable extends Migration
{
    public function up()
    {
        Schema::create('agamas', function (Blueprint $table) {
            // Kolom utama
            $table->string('agama_id', 1)->primary(); // ID Agama (char dengan panjang 2 karakter)
            $table->string('nama', 100); // Nama Agama (maksimal 100 karakter)
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('agama_id'); // Menambahkan index pada kolom 'agama_id'
        });
    }

    public function down()
    {
        Schema::dropIfExists('agamas');
    }
}
