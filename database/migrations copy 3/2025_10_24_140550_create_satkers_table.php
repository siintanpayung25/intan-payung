<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satkers', function (Blueprint $table) {
            // Menentukan kolom satker_id sebagai primary key
            $table->string('satker_id', 10)->primary();  // satker_id sebagai primary key

            // Kolom-kolom yang dibutuhkan
            // Menambahkan kolom instansi_id yang berelasi dengan tabel instansis
            $table->string('instansi_id', 3);  // Pastikan panjangnya sesuai dengan kolom instansi_id di tabel instansis
            $table->string('negara_id', 3);  // kolom negara_id dengan panjang 3
            $table->string('provinsi_id', 5);  // kolom provinsi_id dengan panjang 5
            $table->string('kabupaten_id', 7)->nullable();  // kolom kabupaten_id dengan panjang 7
            $table->string('nama', 70);  // kolom nama dengan panjang 70
            $table->string('keterangan', 100)->nullable();  // kolom keterangan dengan panjang 100

            // Membuat timestamp (created_at, updated_at)
            $table->timestamps();

            // Menambahkan foreign keys dengan cascade on delete dan on update
            $table->foreign('instansi_id')->references('instansi_id')->on('instansis')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('negara_id')->references('negara_id')->on('wilayah_negaras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('provinsi_id')->references('provinsi_id')->on('wilayah_provinsis')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('kabupaten_id')->references('kabupaten_id')->on('wilayah_kabupatens')
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
        Schema::dropIfExists('satkers');
    }
}
