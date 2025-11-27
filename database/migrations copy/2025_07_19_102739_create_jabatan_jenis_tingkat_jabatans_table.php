<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanJenisTingkatJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_jenis_tingkat_jabatans', function (Blueprint $table) {
            $table->string('jenis_tingkat_jabatan_id', 4)->primary(); // ID Jenis Tingkat Jabatan
            $table->string('jenis_jabatan_id', 2); // Foreign key ke tabel jabatan_jenis_jabatans
            $table->string('kode_jenis_tingkat_jabatan', 2); // Kode Jenis Tingkat Jabatan
            $table->string('nama', 100); // Nama Jenis Tingkat Jabatan
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('jenis_tingkat_jabatan_id'); // Menambahkan index pada kolom 'jenis_tingkat_jabatan_id'

            // Menambahkan constraint foreign key
            $table->foreign('jenis_jabatan_id')
                ->references('jenis_jabatan_id')->on('jabatan_jenis_jabatans') // Mengarah ke jenis_jabatan_id di tabel jabatan_jenis_jabatans
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
        Schema::dropIfExists('jabatan_jenis_tingkat_jabatans');
    }
}
