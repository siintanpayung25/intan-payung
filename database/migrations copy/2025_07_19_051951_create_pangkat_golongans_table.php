<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePangkatGolongansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pangkat_golongans', function (Blueprint $table) {
            
            $table->string('golongan_id', 2)->primary(); // Menggunakan 'golongan_id' sebagai primary key
            $table->string('golongan', 3); // Kolom-kolom lain sesuai kebutuhan
            $table->string('ruang', 1);
            $table->string('gol_ruang', 5)->unique();
            $table->string('pangkat', 100);
            $table->timestamps(); // Timestamps untuk mencatat waktu pembuatan dan update

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('golongan_id'); // Menambahkan index pada kolom 'golongan_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Menghapus tabel jika rollback
        Schema::dropIfExists('pangkat_golongans');
    }
}
