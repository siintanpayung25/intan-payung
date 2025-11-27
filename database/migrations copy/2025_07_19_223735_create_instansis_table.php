<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstansisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instansis', function (Blueprint $table) {
            $table->string('instansi_id', 3)->primary(); // ID Instansi
            $table->string('nama', 100); // Nama Instansi            
            $table->string('negara_id', 3); // Kolom negara_id dengan tipe string(2) sesuai dengan tabel wilayah_negaras

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('instansi_id'); // Menambahkan index pada kolom 'instansi_id'

            // Menambahkan foreign key ke tabel wilayah_negaras
            $table->foreign('negara_id')
                ->references('negara_id')
                ->on('wilayah_negaras')
                ->onUpdate('cascade') // <-- Tambahkan ini
                ->onDelete('cascade'); // <-- Sudah ada

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
        Schema::dropIfExists('instansis');
    }
}
