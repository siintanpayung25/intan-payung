<?php

namespace App\Http\Controllers;

use App\Models\Wilayah_Desa;
use Illuminate\Http\Request;

class WilayahDesaController extends Controller
{
    /**
     * Mengambil desa berdasarkan kecamatan_id.
     *
     * @param  string  $kecamatan_id
     * @return \Illuminate\Http\Response
     */
    public function getByKecamatan($kecamatan_id)
    {
        $desa = Wilayah_Desa::where('kecamatan_id', $kecamatan_id)->get();
        return response()->json($desa);
    }
}
