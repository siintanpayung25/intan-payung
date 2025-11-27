<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahProvinsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah_provinsis', function (Blueprint $table) {
            $table->string('provinsi_id', 5)->primary(); // Kolom primary key untuk provinsi
            $table->string('negara_id', 3); // Kolom negara_id yang sesuai dengan tipe data string(2)
            $table->string('kode_provinsi', 2); // Kode Provinsi
            $table->string('nama', 100); // Nama Provinsi
            $table->string('ibukota', 100); // Ibukota Provinsi
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('provinsi_id'); // Menambahkan index pada kolom 'provinsi_id'

            // Menambahkan constraint foreign key
            $table->foreign('negara_id')
                ->references('negara_id')->on('wilayah_negaras') // Mengarah ke negara_id di tabel wilayah_negaras
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
        Schema::dropIfExists('wilayah_provinsis');
    }
}
