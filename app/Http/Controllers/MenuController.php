<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends BaseController
{
    public function store(Request $request)
    {
        // Logika untuk menyimpan menu baru
        try {
            $menu = new Menu();
            $menu->nama = $request->input('nama');
            $menu->url = $request->input('url');
            $menu->level_user_id = Auth::user()->levelUser->id;
            $menu->save();

            // Kirim pesan sukses
            $this->flashSuccess('Menu berhasil disimpan!');
        } catch (\Exception $e) {
            // Jika terjadi error
            $this->flashError('Terjadi kesalahan saat menyimpan menu.');
        }

        return redirect()->route('menus.index');
    }

    public function destroy($id)
    {
        // Logika untuk menghapus menu
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();

            // Kirim pesan sukses
            $this->flashSuccess('Menu berhasil dihapus!');
        } catch (\Exception $e) {
            // Jika terjadi error
            $this->flashError('Terjadi kesalahan saat menghapus menu.');
        }

        return redirect()->route('menus.index');
    }
}
