<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Menu_Submenu_Leveluser_Pivot extends Pivot
{
    protected $table = 'menu_submenu_leveluser_pivot';

    // Relasi ke Menu_Submenu
    public function submenu()
    {
        return $this->belongsTo(Menu_Submenu::class, 'submenu_id');
    }

    // Relasi ke LevelUser
    public function levelUser()
    {
        return $this->belongsTo(LevelUser::class, 'level_user_id');
    }
}
