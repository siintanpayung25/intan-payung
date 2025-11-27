<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->string('jabatan_id', 12)->primary(); // ID Jabatan
            $table->string('jenis_jabatan_id', 2); // Foreign key ke tabel jenis_jabatans
            $table->string('jenis_tingkat_jabatan_id', 4); // Foreign key ke tabel jenis_tingkat_jabatans
            $table->string('tingkat_jabatan_id', 8); // Foreign key ke tabel tingkat_jabatans
            $table->string('kode_jabatan', 4); // Kode Jabatan
            $table->string('nama', 100); // Nama Jabatan
            $table->unsignedInteger('bup'); // Batas Usia Pensiun (BUP)
            $table->string('golongan_maksimal_id', 2); // Foreign Key ke Golongan
            $table->string('kd_jab_simpeg', 6)->nullable(); // Kode Jabatan Simpeg (Optional)
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('jabatan_id'); // Menambahkan index pada kolom 'jabatan_id'

            // Menambahkan constraint foreign key ke tabel jenis_jabatans
            $table->foreign('jenis_jabatan_id')
                ->references('jenis_jabatan_id')->on('jabatan_jenis_jabatans') // Mengarah ke jenis_jabatan_id di tabel jenis_jabatans
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key ke tabel jenis_tingkat_jabatans
            $table->foreign('jenis_tingkat_jabatan_id')
                ->references('jenis_tingkat_jabatan_id')->on('jabatan_jenis_tingkat_jabatans') // Mengarah ke jenis_tingkat_jabatan_id di tabel jenis_tingkat_jabatans
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key ke tabel tingkat_jabatans
            $table->foreign('tingkat_jabatan_id')
                ->references('tingkat_jabatan_id')->on('jabatan_tingkat_jabatans') // Mengarah ke tingkat_jabatan_id di tabel tingkat_jabatans
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key ke tabel golongans
            $table->foreign('golongan_maksimal_id')
                ->references('golongan_id')->on('pangkat_golongans') // Mengarah ke golongan_id di tabel golongans
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
        // Menghapus tabel jabatans
        Schema::dropIfExists('jabatans');
    }
}
