<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unors', function (Blueprint $table) {
            // Kolom-kolom untuk tabel Unor
            $table->string('unor_id', 5)->primary(); // ID Unor, menggunakan string dengan panjang 5 karakter
            $table->string('flag', 14); // Flag, menggunakan string dengan panjang 14 karakter
            $table->string('nama', 100); // Nama Unor, panjang 100 karakter
            $table->string('singkatan', 100); // Singkatan Unor, panjang 100 karakter
            $table->string('lingkup', 50); // Lingkup Unor, panjang 50 karakter
            $table->string('eselon_id', 2)->nullable(); // Eselon ID, bisa null

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('unor_id'); // Menambahkan index pada kolom 'unor_id'

            // Kolom eselon_id sebagai foreign key yang mengarah ke tabel jabatan_eselons
            $table->foreign('eselon_id') // Menambahkan foreign key untuk eselon_id
                ->references('eselon_id') // Mengacu pada kolom eselon_id di tabel jabatan_eselons
                ->on('jabatan_eselons') // Relasi ke tabel jabatan_eselons
                ->onDelete('cascade');   // Jika eselon dihapus, hapus Unor yang terkait

            $table->timestamps(); // Timestamps untuk created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unors');
    }
}
