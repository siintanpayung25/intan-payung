<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendidikanStatusAisTable extends Migration
{
    public function up()
    {
        Schema::create('pendidikan_status_ais', function (Blueprint $table) {
            // Kolom utama
            $table->string('status_ais_id', 1)->primary(); // ID StatusAis (char dengan panjang 1 karakter)
            $table->string('status', 50); // Status (maksimal 50 karakter)                       
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('status_ais_id'); // Menambahkan index pada kolom 'status_ais_id'
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendidikan_status_ais');
    }
}
