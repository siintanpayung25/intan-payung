<?php

namespace App\Http\Controllers;

use App\Models\Wilayah_Negara;
use Illuminate\Http\Request;

class WilayahNegaraController extends Controller
{
    /**
     * Mengambil semua data negara.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $negara = Wilayah_Negara::all();
        return response()->json($negara);
    }

    // Fungsi untuk mengambil daftar Negara dan mengembalikannya dalam bentuk JSON untuk dropdown
    public function ambilNegara(Request $request)
    {
        // Ambil semua negara dengan id dan nama untuk keperluan dropdown
        $negara = Wilayah_Negara::select('id', 'nama')->get();

        // Kembalikan sebagai JSON
        return response()->json($negara);
    }
}
