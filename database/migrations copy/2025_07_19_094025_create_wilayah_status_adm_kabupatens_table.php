<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahStatusAdmKabupatensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah_status_adm_kabupatens', function (Blueprint $table) {
            $table->string('status_adminkab_id', 1)->primary(); // ID status administratif kabupaten (maks. 1 karakter)
            $table->string('nama', 100); // Nama status administratif kabupaten
            $table->timestamps(); // Kolom created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('status_adminkab_id'); // Menambahkan index pada kolom 'status_adminkab_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wilayah_status_adm_kabupatens');
    }
}
