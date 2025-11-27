<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisKelaminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_kelamins', function (Blueprint $table) {
            $table->string('jenis_kelamin_id', 1)->primary(); // Kolom id sebagai primary key, tipe data string dengan panjang 1 karakter      
            $table->string('nama', 15); // Kolom nama jenis kelamin (maksimal 15 karakter)
            $table->string('singkatan', 2); // Kolom singkatan jenis kelamin (maksimal 2 karakter)      
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('jenis_kelamin_id'); // Menambahkan index pada kolom 'jenis_kelamin_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_kelamins');
    }
}
