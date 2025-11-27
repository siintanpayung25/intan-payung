<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan_Kategori;

class PelatihanJenisController extends Controller
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

    public function getJenisByKategori($kategori_id)
    {
        // Ambil jenis pelatihan yang terhubung dengan kategori_id melalui pivot
        $kategori = Pelatihan_Kategori::find($kategori_id);

        if (!$kategori) {
            return response()->json([], 404); // Jika kategori tidak ditemukan, kembalikan 404
        }

        // Ambil jenis pelatihan yang terhubung melalui pivot table
        $jenis = $kategori->jenis;

        // Kembalikan data jenis pelatihan dalam bentuk JSON
        return response()->json($jenis);
    }
}
