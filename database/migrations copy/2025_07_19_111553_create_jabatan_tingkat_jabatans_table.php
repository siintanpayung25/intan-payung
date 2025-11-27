<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanTingkatJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_tingkat_jabatans', function (Blueprint $table) {
            $table->string('tingkat_jabatan_id', 8)->primary(); // ID Tingkat Jabatan
            $table->string('jenis_jabatan_id', 2); // Foreign key ke tabel jenis_jabatans
            $table->string('jenis_tingkat_jabatan_id', 4); // Foreign key ke tabel jenis_tingkat_jabatans
            $table->string('kode_tingkat_jabatan', 2); // Kode Tingkat Jabatan
            $table->string('nama', 100); // Nama Tingkat Jabatan
            $table->string('eselon_id', 2); // ID Eselon (Foreign Key ke tabel eselons)
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('tingkat_jabatan_id'); // Menambahkan index pada kolom 'tingkat_jabatan_id'

            // Menambahkan constraint foreign key ke tabel jenis_jabatans
            $table->foreign('jenis_jabatan_id')
                ->references('jenis_jabatan_id')->on('jabatan_jenis_jabatans') // Mengarah ke jenis_jabatan_id di tabel jabatan_jenis_jabatans
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key ke tabel jenis_tingkat_jabatans
            $table->foreign('jenis_tingkat_jabatan_id')
                ->references('jenis_tingkat_jabatan_id')->on('jabatan_jenis_tingkat_jabatans') // Mengarah ke jenis_tingkat_jabatan_id di tabel jabatan_jenis_tingkat_jabatans
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Menambahkan constraint foreign key ke tabel eselons
            $table->foreign('eselon_id')
                ->references('eselon_id')->on('jabatan_eselons') // Mengarah ke eselon_id di tabel eselons
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
        Schema::dropIfExists('jabatan_tingkat_jabatans');
    }
}
