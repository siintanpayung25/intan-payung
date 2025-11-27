<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahKabupatensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah_kabupatens', function (Blueprint $table) {
            $table->string('kabupaten_id', 7)->primary(); // Kolom primary key untuk kabupaten
            $table->string('negara_id', 3); // Kolom negara_id yang sesuai dengan tipe data string(2)
            $table->string('provinsi_id', 5); // Kolom provinsi_id yang sesuai dengan tipe data string(4)
            $table->string('status_adminkab_id', 1); // Kolom status_adminkab_id yang sesuai dengan tipe data string(1)
            $table->string('kode_kabupaten', 2); // Kode Kabupaten
            $table->string('nama', 100); // Nama Kabupaten
            $table->string('ibukota', 100); // Ibukota Kabupaten
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('kabupaten_id'); // Menambahkan index pada kolom 'kabupaten_id'

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

            // Menambahkan constraint foreign key untuk status_adminkab_id
            $table->foreign('status_adminkab_id')
                ->references('status_adminkab_id')->on('wilayah_status_adm_kabupatens') // Mengarah ke status_adminkab_id di tabel wilayah_status_adm_kabupatens
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
        Schema::dropIfExists('wilayah_kabupatens');
    }
}
