<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan_Kategori;
use Illuminate\Http\Request;

class PelatihanKategoriController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil data Pelatihan Kategori dengan filter dan sorting jika diperlukan
            $pelatihanKategori = Pelatihan_Kategori::select('kategori_id', 'nama');

            // Jika ada pencarian
            if ($search = $request->get('search')['value']) {
                $pelatihanKategori->where('nama', 'like', "%$search%");
            }

            // Ambil data yang diperlukan (limit dan offset untuk paging)
            $start = $request->get('start');
            $length = $request->get('length');

            $pelatihanKategori = $pelatihanKategori
                ->skip($start) // offset
                ->take($length) // limit
                ->get();

            // Hitung total data untuk paging
            $totalRecords = Pelatihan_Kategori::count();

            return response()->json([
                'draw' => $request->get('draw'), // Number of times the request has been sent
                'recordsTotal' => $totalRecords, // Total records
                'recordsFiltered' => $totalRecords, // Total records after filtering (same here, as we are not doing advanced filtering)
                'data' => $pelatihanKategori // Data rows
            ]);
        }

        return view('pelatihan.kategori.index');
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
}
