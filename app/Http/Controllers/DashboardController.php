<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function index()
    {
        // Data menus dan user sudah tersedia karena diwarisi dari BaseController
        return view('dashboard'); // Tidak perlu mengambil data lagi, karena sudah dibagikan
    }
}
