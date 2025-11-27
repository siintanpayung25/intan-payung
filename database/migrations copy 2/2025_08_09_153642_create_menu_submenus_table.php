<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuSubmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_submenus', function (Blueprint $table) {
            // Definisikan kolom untuk primary key gabungan (menu_id dan submenu_id)
            $table->string('submenu_id', 4);
            $table->string('menu_id', 2);
            $table->string('nama');
            $table->string('url');
            $table->integer('urutan')->default(0);

            // Menambahkan index untuk submenu_id
            $table->index('submenu_id'); // <-- Menambahkan index pada submenu_id

            // Membuat foreign key untuk menu_id ke tabel menus
            $table->foreign('menu_id')->references('menu_id')->on('menus')->onDelete('cascade')->onUpdate('cascade');

            // Menetapkan primary key gabungan
            $table->primary(['menu_id', 'submenu_id']);

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_submenus');
    }
}
