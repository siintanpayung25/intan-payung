<?php

namespace App\Http\Controllers;

use App\Models\Wilayah_Kecamatan;
use Illuminate\Http\Request;

class WilayahKecamatanController extends Controller
{
    /**
     * Mengambil kecamatan berdasarkan kabupaten_id.
     *
     * @param  string  $kabupaten_id
     * @return \Illuminate\Http\Response
     */
    public function getByKabupaten($kabupaten_id)
    {
        $kecamatan = Wilayah_Kecamatan::where('kabupaten_id', $kabupaten_id)->get();
        return response()->json($kecamatan);
    }
}
