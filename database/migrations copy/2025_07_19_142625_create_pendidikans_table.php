<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendidikansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendidikans', function (Blueprint $table) {
            $table->string('pendidikan_id', 6)->primary(); // ID Pendidikan
            $table->string('tingkat_pendidikan_id', 2); // Foreign key ke tabel pendidikan_tingkat_pendidikans
            $table->string('kode_fakultas', 2); // Kode Fakultas
            $table->string('kode_bidang', 2); // Kode Bidang
            $table->string('nama', 100); // Nama Pendidikan
            $table->string('golongan_maksimal_id', 2); // Foreign Key ke Golongan
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('pendidikan_id'); // Menambahkan index pada kolom 'pendidikan_id'

            // Menambahkan constraint foreign key ke tabel pendidikan_tingkat_pendidikans
            $table->foreign('tingkat_pendidikan_id')
                ->references('tingkat_pendidikan_id')->on('pendidikan_tingkat_pendidikans') // Mengarah ke tingkat_pendidikan_id di tabel pendidikan_tingkat_pendidikans
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
        // Menghapus tabel pendidikans
        Schema::dropIfExists('pendidikans');
    }
}
