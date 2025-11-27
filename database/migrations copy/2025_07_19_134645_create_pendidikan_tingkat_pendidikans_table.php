<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendidikanTingkatPendidikansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendidikan_tingkat_pendidikans', function (Blueprint $table) {
            $table->string('tingkat_pendidikan_id', 2)->primary(); // ID Tingkat Pendidikan
            $table->string('nama', 100); // Nama Tingkat Pendidikan
            $table->string('kode_simpeg', 1); // Kode Simpeg
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('tingkat_pendidikan_id'); // Menambahkan index pada kolom 'tingkat_pendidikan_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendidikan_tingkat_pendidikans');
    }
}
