<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanTnaTable extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel pelatihan_tna.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_tna', function (Blueprint $table) {
            // Kolom utama untuk primary key dan fields lainnya
            $table->string('tna_id', 27)->primary(); // tna_id sebagai primary key
            $table->string('nip')->nullable(); // Kolom nip untuk relasi dengan 
            $table->string('sifat_tna_id', 2); // Kolom sifat_tna_id dengan tipe yang sama dengan sifat_tna_id di pelatihan_sifat_tna
            $table->string('kode_tna', 2); // Kode pelatihan, panjang 2 karakter
            $table->string('nama'); // Nama pelatihan
            $table->string('deskripsi', 150)->nullable(); // Deskripsi pelatihan
            $table->year('tahun'); // Tahun pelatihan (4 digit)

            // Menambahkan kolom status_tna untuk menyimpan status checklist (boolean)
            $table->boolean('status_tna')->default(false); // status_tna, defaultnya 'false' (tidak aktif)

            // Timestamps untuk created_at dan updated_at
            $table->timestamps();

            // Index tambahan (jika diperlukan untuk query performance)
            $table->index('nip');
            $table->index('sifat_tna_id');
        });

        // Deklarasi foreign keys di bawah ini:
        Schema::table('pelatihan_tna', function (Blueprint $table) {
            // Foreign key untuk relasi dengan tabel pegawai (nip)
            $table->foreign('nip')->references('nip')->on('pegawais')
                ->onDelete('cascade') // Jika data pegawai dihapus, data di pelatihan_tna juga dihapus
                ->onUpdate('cascade'); // Jika data pegawai diupdate, data di pelatihan_tna juga diupdate

            // Foreign key untuk relasi dengan tabel pelatihan_sifat_tna (sifat_tna_id)
            $table->foreign('sifat_tna_id')->references('sifat_tna_id')->on('pelatihan_sifat_tna')
                ->onDelete('cascade') // Jika data pelatihan_sifat_tna dihapus, data di pelatihan_tna juga dihapus
                ->onUpdate('cascade'); // Jika data pelatihan_sifat_tna diupdate, data di pelatihan_tna juga diupdate
        });
    }

    /**
     * Bungkus migrasi jika ingin menghapus tabel pelatihan_tna.
     *
     * @return void
     */
    public function down()
    {
        // Menghapus tabel pelatihan_tna jika migrasi dibatalkan
        Schema::dropIfExists('pelatihan_tna');
    }
}
