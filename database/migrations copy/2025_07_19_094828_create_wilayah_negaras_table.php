<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahNegarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah_negaras', function (Blueprint $table) {
            $table->string('negara_id', 3)->primary();
            $table->string('nama', 100)->nullable(); // Nama Negara
            $table->string('ibukota', 100); // Ibukota Negara
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('negara_id'); // Menambahkan index pada kolom 'negara_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wilayah_negaras');
    }
}
