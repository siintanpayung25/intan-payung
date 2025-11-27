<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu_Submenu extends Model
{
    protected $table = 'menu_submenus';

    // Tentukan primary key
    protected $primaryKey = 'submenu_id';  // Sesuaikan dengan kolom primary key

    // Tidak ada auto-increment untuk submenu_id
    public $incrementing = false;

    // Relasi ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    // Relasi ke LevelUser melalui pivot table
    public function levelUsers()
    {
        return $this->belongsToMany(LevelUser::class, 'menu_submenu_leveluser_pivot', 'submenu_id', 'level_user_id');
    }
}
