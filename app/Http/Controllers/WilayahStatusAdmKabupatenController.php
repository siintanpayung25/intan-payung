<?php

namespace App\Http\Controllers;

use App\Models\Wilayah_StatusAdmKabupaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WilayahStatusAdmKabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Mengambil nama status administrasi kabupaten berdasarkan status_adminkab_id
     */
    // public function ambilStatusKabupaten($status_adminkab_id)
    // {
    //     // Debug: Cek apakah parameter status_adminkab_id diterima dengan benar
    //     dd($status_adminkab_id); // Cek ID yang dikirim

    //     // Cek apakah ada status administrasi berdasarkan ID yang diberikan
    //     $statusKabupaten = Wilayah_StatusAdmKabupaten::where('status_adminkab_id', $status_adminkab_id)->first();

    //     // Debug: Cek apakah data ditemukan atau tidak
    //     dd($statusKabupaten);

    //     if ($statusKabupaten) {
    //         return response()->json([
    //             'nama' => $statusKabupaten->nama
    //         ]);
    //     } else {
    //         return response()->json([
    //             'error' => 'Status administrasi kabupaten tidak ditemukan'
    //         ], 404);
    //     }
    // }

    public function ambilStatusKabupaten($status_adminkab_id)
    {
        // Mengambil data status administrasi berdasarkan ID
        $status = Wilayah_StatusAdmKabupaten::find($status_adminkab_id);

        // Jika data status tidak ditemukan
        if (!$status) {
            return response()->json(['error' => 'Status administrasi tidak ditemukan.'], 404);
        }

        // Mengembalikan data status administrasi
        return response()->json($status, 200);
    }
}
