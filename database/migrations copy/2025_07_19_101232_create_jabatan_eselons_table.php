<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanEselonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_eselons', function (Blueprint $table) {
            $table->string('eselon_id', 2)->primary(); // ID Eselon
            $table->string('nama', 50); // Nama Eselon
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('eselon_id'); // Menambahkan index pada kolom 'eselon_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan_eselons');
    }
}
