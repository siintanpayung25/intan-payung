<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePangkatJenisKenaikanPangkatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pangkat_jenis_kenaikan_pangkats', function (Blueprint $table) {
            // Kolom 'jenis_kp_id' sebagai primary key, dengan tipe string
            $table->string('jenis_kp_id', 2)->primary();
            $table->string('nama', 100); // Kolom nama jenis kenaikan pangkat
            $table->timestamps(); // Menambahkan timestamp (created_at, updated_at)

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('jenis_kp_id'); // Menambahkan index pada kolom 'jenis_kp_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Menghapus tabel jenis_kenaikan_pangkats jika rollback
        Schema::dropIfExists('pangkat_jenis_kenaikan_pangkats');
    }
}
