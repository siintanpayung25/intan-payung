<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanSifatTnaTable extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel pelatihan_sifat_tna.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_sifat_tna', function (Blueprint $table) {
            $table->string('sifat_tna_id', 2)->primary();
            $table->string('nama', 100); // Nama sifat pelatihan
            $table->string('deskripsi', 100)->nullable(); // Deskripsi sifat pelatihan
            $table->timestamps();
        });
    }

    /**
     * Bungkus migrasi jika ingin menghapus tabel pelatihan_sifat_tna.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelatihan_sifat_tna');
    }
}
