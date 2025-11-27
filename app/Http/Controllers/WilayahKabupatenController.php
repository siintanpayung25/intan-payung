<?php

namespace App\Http\Controllers;

use App\Models\Wilayah_Kabupaten;
use App\Models\Wilayah_StatusAdministratifKabupaten;
use Illuminate\Http\Request;

class WilayahKabupatenController extends Controller
{
    /**
     * Mengambil kabupaten berdasarkan provinsi_id.
     *
     * @param  string  $provinsi_id
     * @return \Illuminate\Http\Response
     */
    public function getByProvinsi($provinsi_id)
    {
        $kabupaten = Wilayah_Kabupaten::where('provinsi_id', $provinsi_id)->get();
        return response()->json($kabupaten);
    }

    // Ambil kabupaten saja
    public function ambilKabupaten($negara_id, $provinsi_id)
    {
        try {
            // Ambil kabupaten berdasarkan negara_id dan provinsi_id
            $kabupatens = Wilayah_Kabupaten::where('negara_id', $negara_id)
                ->where('provinsi_id', $provinsi_id)
                ->get();

            if ($kabupatens->isEmpty()) {
                return response()->json(['message' => 'Tidak ada data ditemukan'], 404);
            }

            return response()->json($kabupatens, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Ambil kabupaten dengan wilayah administrasi, Kabupaten atau Kota
    // Di WilayahKabupatenController

    public function ambilKabupatenDanAdministrasi($negara_id, $provinsi_id)
    {
        try {
            // Ambil kabupaten berdasarkan negara_id dan provinsi_id
            $kabupatens = Wilayah_Kabupaten::where('negara_id', $negara_id)
                ->where('provinsi_id', $provinsi_id)
                ->get(['kabupaten_id', 'nama', 'status_adminkab_id']);  // Pastikan 'status_adminkab_id' ada

            if ($kabupatens->isEmpty()) {
                return response()->json(['message' => 'Tidak ada data ditemukan'], 404);
            }

            return response()->json($kabupatens, 200);  // Kembalikan kabupaten beserta status_adminkab_id
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
