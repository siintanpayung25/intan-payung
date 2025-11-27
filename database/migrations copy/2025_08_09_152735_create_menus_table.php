<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->string('menu_id', 2)->primary();  // Menggunakan menu_id sebagai primary key (2 digit)
            $table->string('nama');
            $table->string('ikon')->nullable();
            $table->integer('urutan');
            $table->string('url');
            $table->string('level_user_id');  // Menambahkan kolom level_user_id (di sini harus berupa string)
            
            // Mengubah foreign key agar merujuk ke level_user_id di tabel level_users
            $table->foreign('level_user_id') // Foreign key ke level_users
                  ->references('level_user_id')  // Kolom level_user_id di level_users
                  ->on('level_users')  // Pada tabel level_users
                  ->onDelete('cascade')  // Menghapus menu ketika level_user dihapus
                  ->onUpdate('cascade'); // Menambahkan onUpdate cascade

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
