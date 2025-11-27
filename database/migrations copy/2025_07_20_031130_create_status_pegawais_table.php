<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusPegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_pegawais', function (Blueprint $table) {           
            $table->string('status_pegawai_id', 2)->primary(); // Kolom id sebagai primary key, tipe data string dengan panjang 2 karakter           
            $table->string('nama', 100); // Kolom nama status pegawai (maksimal 100 karakter)            
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('status_pegawai_id'); // Menambahkan index pada kolom 'status_pegawai_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_pegawais');
    }
}
