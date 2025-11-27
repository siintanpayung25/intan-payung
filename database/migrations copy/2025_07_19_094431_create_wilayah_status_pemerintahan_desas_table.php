<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahStatusPemerintahanDesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah_status_pemerintahan_desas', function (Blueprint $table) {
            // Kolom 'id' bertipe string dan menjadi primary key
            $table->string('status_pemdesa_id', 1)->primary(); // ID dengan panjang 1 karakter, sebagai primary key
            $table->string('nama', 100); // Kolom 'nama' untuk menyimpan nama status pemerintahan desa
            $table->timestamps(); // Kolom timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('status_pemdesa_id'); // Menambahkan index pada kolom 'status_pemdesa_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wilayah_status_pemerintahan_desas');
    }
}
