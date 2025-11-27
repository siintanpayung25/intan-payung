<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelUser extends Model
{
    protected $primaryKey = 'level_user_id';

    public $incrementing = false;
    protected $keyType = 'string'; // Karena level_user_id adalah string

    // Relasi many-to-many dengan Menu_Submenu melalui pivot table
    public function submenus()
    {
        return $this->belongsToMany(Menu_Submenu::class, 'menu_submenu_leveluser_pivot', 'level_user_id', 'submenu_id');
    }

    // Relasi many-to-many dengan Menu
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_submenu_leveluser_pivot', 'level_user_id', 'menu_id');
    }
}
