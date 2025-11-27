<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanStatusFungsionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_status_fungsionals', function (Blueprint $table) {
            $table->string('status_fungsional_id', 1)->primary(); // ID Status Fungsional
            $table->string('status', 50); // Status Fungsional
            $table->timestamps();

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('status_fungsional_id'); // Menambahkan index pada kolom 'status_fungsional_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan_status_fungsionals');
    }
}
