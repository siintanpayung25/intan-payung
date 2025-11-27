<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanJenjangJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_jenjang_jabatans', function (Blueprint $table) {
            $table->string('jenjang_jafung_id', 10)->primary(); // ID Jenjang Jabatan Fungsional
            $table->string('tingkat_jabatan_id', 8); // Foreign Key ke tingkat_jabatans           
            $table->string('kode_jenjang_jabatan_fungsional', 2); // Kode Jenjang Jabatan Fungsional            
            $table->string('nama', 100); // Nama Jenjang Jabatan Fungsional
            $table->timestamps(); // Timestamps

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('jenjang_jafung_id'); // Menambahkan index pada kolom 'jenjang_jafung_id'

            // Menambahkan constraint foreign key ke tabel tingkat_jabatans
            $table->foreign('tingkat_jabatan_id')
                ->references('tingkat_jabatan_id')->on('jabatan_tingkat_jabatans') // Mengarah ke tingkat_jabatan_id di tabel tingkat_jabatans
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Menghapus tabel jabatan_jenjang_jabatan_fungsionals
        Schema::dropIfExists('jabatan_jenjang_jabatans');
    }
}
