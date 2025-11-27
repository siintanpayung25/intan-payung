<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanKategorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_kategoris', function (Blueprint $table) {
            // Kolom Pelatihan Kategori
            $table->string('kategori_id', 3)->primary(); // ID Pelatihan Kategori, tipe text 3 digit
            $table->string('bentuk_id', 1); // ID Bentuk, tipe text
            $table->string('kode_kategori', 2); // Kode Kategori, tipe text 2 digit
            $table->string('nama', 100); // Nama Kategori, tipe text 100 karakter
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom bentuk_id untuk query lebih cepat
            $table->index('kategori_id'); // Menambahkan index pada kolom 'kategori_id'
            $table->index('bentuk_id'); // Menambahkan index pada kolom 'bentuk_id'

            // Menambahkan Foreign Key untuk bentuk_id yang merujuk ke pelatihan_bentuks
            // Tambahkan cascade update selain delete
            $table->foreign('bentuk_id')
                ->references('bentuk_id')
                ->on('pelatihan_bentuks')
                ->onDelete('cascade')  // Cascade delete jika data bentuk_id dihapus
                ->onUpdate('cascade'); // Cascade update jika data bentuk_id diupdate
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelatihan_kategoris');
    }
}
