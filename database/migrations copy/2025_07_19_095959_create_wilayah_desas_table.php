<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahDesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah_desas', function (Blueprint $table) {
            // Kolom primary key untuk desa (gabungan kecamatan_id + kode_desa)
            $table->string('desa_id', 13)->primary(); // Kolom negara_id yang sesuai dengan tipe data string(2)
            $table->string('negara_id', 3); // Kolom provinsi_id yang sesuai dengan tipe data string(4)
            $table->string('provinsi_id', 5); // Kolom kabupaten_id yang sesuai dengan tipe data string(6)
            $table->string('kabupaten_id', 7); // Kolom kecamatan_id yang sesuai dengan tipe data string(9)
            $table->string('kecamatan_id', 10); // Kode Desa (3 karakter)
            $table->string('kode_desa', 3); // Nama Desa (maksimal 100 karakter)
            $table->string('nama', 100); // Status Pemerintahan Desa (nullable karena bisa kosong)
            $table->string('status_pemdesa_id', 1)->nullable(); // Timestamps untuk created_at dan updated_at
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('desa_id'); // Menambahkan index pada kolom 'desa_id'

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

            // Menambahkan constraint foreign key untuk kecamatan_id
            $table->foreign('kecamatan_id')
                ->references('kecamatan_id')->on('wilayah_kecamatans') // Mengarah ke kecamatan_id di tabel wilayah_kecamatans
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key untuk status_pemerintahan_id
            $table->foreign('status_pemdesa_id')
                ->references('status_pemdesa_id')->on('wilayah_status_pemerintahan_desas') // Mengarah ke status_pemerintahan_id di tabel wilayah_status_pemerintahan_desas
                ->onDelete('set null') // Jika status pemerintahan desa dihapus, biarkan null
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
        Schema::dropIfExists('wilayah_desas');
    }
}
