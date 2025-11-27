<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\Menu_Submenu;
use App\Models\LevelUser;
// use App\Models\Wilayah_Kabupaten; <-- TIDAK PERLU LAGI DIIMPOR
use Illuminate\Support\Facades\Session;

class BaseController extends Controller
{
    protected $menus;
    protected $user;

    public function __construct()
    {
        // Ambil data user yang sedang login
        $this->user = Auth::user();

        // Periksa apakah user memiliki pegawai terkait
        if ($this->user && $this->user->pegawai) {

            // Ambil level_user_id dari pegawai yang terkait
            $levelUserId = $this->user->pegawai->level_user_id;

            // Ambil nama level user berdasarkan level_user_id
            $levelUser = LevelUser::find($levelUserId);
            $NamalevelUser = $levelUser ? $levelUser->nama : null;

            // ========================================================
            // PERBAIKAN: MENGAMBIL PROVINSI_ID & KABUPATEN_ID LANGSUNG DARI PEGAWAI
            // ========================================================
            $provinsiId = $this->user->pegawai->provinsi_id; // <-- Langsung dari model Pegawai
            $kabupatenId = $this->user->pegawai->kabupaten_id; // <-- Langsung dari model Pegawai
            // ========================================================

            // Simpan SEMUA data ke session
            session()->put('NamalevelUser', $NamalevelUser);
            session()->put('nip', $this->user->pegawai->nip);
            session()->put('unor_id', $this->user->pegawai->unor_id);
            session()->put('kabupaten_id', $kabupatenId);
            session()->put('provinsi_id', $provinsiId); // <-- Simpan provinsi_id baru

            // Bagikan SEMUA data ke seluruh view
            view()->share('NamalevelUser', $NamalevelUser);
            view()->share('nip', session('nip'));
            view()->share('unor_id', session('unor_id'));
            view()->share('kabupaten_id', session('kabupaten_id'));
            view()->share('provinsi_id', session('provinsi_id')); // <-- Bagikan provinsi_id baru

            // ... (Logika Menu dan Submenu tetap sama) ...

            // Ambil submenu yang relevan dengan level_user_id
            $submenus = Menu_Submenu::whereHas('levelUsers', function ($query) use ($levelUserId) {
                $query->where('menu_submenu_leveluser_pivot.level_user_id', $levelUserId);
            })
                ->orderBy('urutan')
                ->get();

            // Ambil menu yang terkait dengan submenu yang ditemukan
            $menusFromSubmenu = $submenus->map(function ($submenu) {
                return $submenu->menu;
            })->unique('menu_id');

            // Ambil menu yang tidak memiliki submenu
            $menusWithoutSubmenu = Menu::doesntHave('submenus')
                ->orderBy('urutan')
                ->get();

            // Gabungkan dan urutkan menu
            $menus = $menusFromSubmenu->merge($menusWithoutSubmenu)->unique('menu_id');
            $menus = $menus->sortBy(function ($menu) {
                return $menu->urutan;
            });

            // Membagikan data submenu, menu, dan user ke seluruh view
            view()->share('submenus', $submenus);
            view()->share('menus', $menus);
            view()->share('user', $this->user);
        } else {
            // Jika user tidak memiliki pegawai terkait, beri pesan error atau handling lainnya
            abort(403, 'Akses ditolak, data pegawai tidak ditemukan.');
        }
    }

    // ... (Fungsi Flash Messages di bawah tidak berubah) ...

    /**
     * Menampilkan pesan sukses
     */
    protected function flashSuccess($message)
    {
        Session::flash('success', $message);
    }

    /**
     * Menampilkan pesan error
     */
    protected function flashError($message)
    {
        Session::flash('error', $message);
    }

    /**
     * Menampilkan pesan informasi
     */
    protected function flashInfo($message)
    {
        Session::flash('info', $message);
    }

    /**
     * Menampilkan pesan warning
     */
    protected function flashWarning($message)
    {
        Session::flash('warning', $message);
    }
}
