<?php

namespace App\Http\Controllers;

use App\Models\Wilayah_Provinsi;
use Illuminate\Http\Request;

class WilayahProvinsiController extends Controller
{
    /**
     * Mengambil provinsi berdasarkan negara_id.
     *
     * @param  string  $negara_id
     * @return \Illuminate\Http\Response
     */
    public function getByNegara($negara_id)
    {
        $provinsi = Wilayah_Provinsi::where('negara_id', $negara_id)->get();
        return response()->json($provinsi);
    }

    public function ambilProvinsi($negara_id)
    {
        // Ambil provinsi berdasarkan negara
        $provinsis = Wilayah_Provinsi::where('negara_id', $negara_id)->get();

        return response()->json($provinsis);
    }
}
