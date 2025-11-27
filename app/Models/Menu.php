<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $primaryKey = 'menu_id';  // Menggunakan menu_id sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';  // Karena menu_id adalah string

    protected $fillable = [
        'menu_id',
        'nama',
        'ikon',
        'urutan',
        'url',
    ];

    // Relasi dengan Menu_Submenu
    public function submenus()
    {
        return $this->hasMany(Menu_Submenu::class, 'menu_id', 'menu_id');
    }

    // Relasi many-to-many dengan LevelUser melalui pivot table menu_submenu_leveluser_pivot
    public function levelUsers()
    {
        return $this->belongsToMany(LevelUser::class, 'menu_submenu_leveluser_pivot', 'menu_id', 'level_user_id');
    }
}
