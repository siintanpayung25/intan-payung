<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends BaseController // Mewarisi BaseController
{
    public function index()
    {
        // Data menus dan user sudah tersedia karena diwarisi dari BaseController
        return view('home'); // Tidak perlu mengambil data lagi, karena sudah dibagikan
    }
}
