<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahKecamatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah_kecamatans', function (Blueprint $table) {
            $table->string('kecamatan_id', 10)->primary(); // Kolom primary key untuk kecamatan
            $table->string('negara_id', 3); // Kolom negara_id yang sesuai dengan tipe data string(2)
            $table->string('provinsi_id', 5); // Kolom provinsi_id yang sesuai dengan tipe data string(4)
            $table->string('kabupaten_id', 7); // Kolom kabupaten_id yang sesuai dengan tipe data string(7)
            $table->string('kode_kecamatan', 3); // Kode Kecamatan
            $table->string('nama', 100); // Nama Kecamatan
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('kecamatan_id'); // Menambahkan index pada kolom 'kecamatan_id'

            // Menambahkan constraint foreign key untuk negara_id
            $table->foreign('negara_id')
                ->references('negara_id')->on('wilayah_negaras') // Mengarah ke negara_id di tabel wilayah_negaras
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key untuk provinsi_id
            $table->foreign('provinsi_id')
                ->references('provinsi_id')->on('wilayah_provinsis') // Mengarah ke provinsi_id di tabel wilayah_provinsis
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key untuk kabupaten_id
            $table->foreign('kabupaten_id')
                ->references('kabupaten_id')->on('wilayah_kabupatens') // Mengarah ke kabupaten_id di tabel wilayah_kabupatens
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
        Schema::dropIfExists('wilayah_kecamatans');
    }
}
