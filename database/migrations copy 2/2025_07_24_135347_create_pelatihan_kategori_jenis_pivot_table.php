<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanKategoriJenisPivotTable extends Migration
{
    public function up()
    {
        Schema::create('pelatihan_kategori_jenis_pivot', function (Blueprint $table) {
            $table->string('kategori_jenis_id', 5)->primary(); // Primary key sebagai string dengan panjang 5 karakter
            $table->string('kategori_id', 3);  // ID Kategori Pelatihan (mengacu pada pelatihan_kategoris)
            $table->string('jenis_id', 2);     // ID Jenis Pelatihan (mengacu pada pelatihan_jeniss)
            $table->timestamps();

            // Menambahkan Foreign Key untuk kategori_id yang merujuk ke pelatihan_kategoris
            $table->foreign('kategori_id')
                ->references('kategori_id')
                ->on('pelatihan_kategoris')
                ->onDelete('cascade')  // Cascade delete jika kategori dihapus
                ->onUpdate('cascade'); // Cascade update jika kategori diupdate

            // Menambahkan Foreign Key untuk jenis_id yang merujuk ke pelatihan_jeniss
            $table->foreign('jenis_id')
                ->references('jenis_id')
                ->on('pelatihan_jeniss')
                ->onDelete('cascade')  // Cascade delete jika jenis dihapus
                ->onUpdate('cascade'); // Cascade update jika jenis diupdate

            // Menambahkan index untuk mempercepat query
            $table->index('kategori_id');
            $table->index('jenis_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelatihan_kategori_jenis_pivot');
    }
}
