<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuSubmenuLevelUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('menu_submenu_leveluser_pivot', function (Blueprint $table) {
            // Kolom submenu_leveluser_id yang akan menjadi primary key
            $table->string('submenu_leveluser_id', 6)->primary();  // Tipe text dengan panjang 6 karakter

            // Foreign key ke Submenu
            $table->string('submenu_id', 4);  // Tipe string untuk submenu_id (sesuai dengan ukuran 4 karakter submenu_id)
            $table->foreign('submenu_id')
                ->references('submenu_id')  // Merujuk ke submenu_id pada tabel menu_submenus
                ->on('menu_submenus')  // Pada tabel menu_submenus
                ->onDelete('cascade')  // Menghapus data jika submenu dihapus
                ->onUpdate('cascade'); // Update submenu_id jika submenu diupdate

            // Foreign key ke LevelUser
            $table->string('level_user_id', 2);  // Tipe string untuk level_user_id
            $table->foreign('level_user_id')
                ->references('level_user_id')  // Merujuk ke level_user_id pada tabel level_users
                ->on('level_users')  // Pada tabel level_users
                ->onDelete('cascade')  // Menghapus data jika level_user dihapus
                ->onUpdate('cascade'); // Update level_user_id jika level_user diupdate

            // Menambahkan timestamps
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_submenu_leveluser_pivot');
    }
}
