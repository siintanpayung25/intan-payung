<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID User
            $table->string('username')->nullable(); // Kolom username
            $table->string('nip', 18); // NIP 18 karakter, berelasi dengan pegawai
            $table->string('email')->unique(); // Email user
            $table->string('password'); // Password user
            $table->timestamps(); // Created_at dan updated_at

            // Menambahkan index pada kolom id untuk query lebih cepat
            $table->index('id'); // Menambahkan index pada kolom 'id'

            // Relasi ke tabel pegawai berdasarkan nip
            $table->foreign('nip') // Kolom nip di tabel users
                  ->references('nip') // Mengarah ke nip di tabel pegawai
                  ->on('pegawais') // Tabel yang menjadi referensi
                  ->onDelete('cascade') // Jika data pegawai dihapus, data user juga ikut terhapus
                  ->onUpdate('cascade'); // Jika nip di tabel pegawai diupdate, nip di tabel users juga ikut terupdate
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
