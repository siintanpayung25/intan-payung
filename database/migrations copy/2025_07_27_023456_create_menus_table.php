<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->string('menu_id')->primary();  // Ganti id menjadi menu_id bertipe string
            $table->string('nama');  // Nama menu
            $table->string('ikon')->nullable();  // Ikon (opsional)
            $table->integer('urutan')->default(0);  // Urutan menu
            $table->string('url');  // URL untuk menu
            $table->string('parent_id')->nullable();  // Parent menu (untuk submenu), tipe string
            $table->timestamps();

            // Menambahkan index untuk parent_id
            $table->index('parent_id'); 

            // Menambahkan index untuk menu_id
            $table->index('menu_id');

            // Foreign key untuk parent menu (relasi ke menu induk)
            $table->foreign('parent_id')->references('menu_id')->on('menus')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
