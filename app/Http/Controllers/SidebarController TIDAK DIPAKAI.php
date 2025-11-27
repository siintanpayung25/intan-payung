<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\LevelUser;
use App\Models\Menu_Submenu_Leveluser_Pivot;
use Illuminate\Support\Facades\Auth;

class SidebarController extends BaseController
{
    /**
     * Menampilkan sidebar dengan menu dan submenu yang relevan berdasarkan level user.
     *
     * @return \Illuminate\View\View
     */

    public function showSidebar()
    {
        // Ambil level_user_id dari session atau pengguna yang sedang login
        $levelUserId = Auth::user()->pegawai->levelUser->level_user_id;

        // Ambil menu dan submenu berdasarkan level_user_id
        $menus = Menu::whereHas('levelUsers', function ($query) use ($levelUserId) {
            $query->where('level_user_id', $levelUserId);
        })->with(['submenus' => function ($query) use ($levelUserId) {
            $query->whereHas('levelUsers', function ($query) use ($levelUserId) {
                $query->where('level_user_id', $levelUserId);
            });
        }])->get();

        // Kelompokkan submenu berdasarkan menu_id
        $groupedSubmenus = $menus->mapWithKeys(function ($menu) {
            return [$menu->menu_id => $menu->submenus];
        });

        // Kirim data ke view
        return view('layouts.sidebar', compact('menus', 'groupedSubmenus')); // pastikan mengirim groupedSubmenus
    }
}
