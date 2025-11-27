<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanJenisJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_jenis_jabatans', function (Blueprint $table) {
            $table->string('jenis_jabatan_id', 2)->primary(); // ID Jenis Jabatan
            $table->string('nama', 50); // Nama Jenis Jabatan
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('jenis_jabatan_id'); // Menambahkan index pada kolom 'jenis_jabatan_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan_jenis_jabatans');
    }
}
