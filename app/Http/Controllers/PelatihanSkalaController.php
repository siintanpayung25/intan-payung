<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan_Skala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PelatihanSkalaController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Mengambil data pelatihan skala tanpa relasi, hanya kolom yang diperlukan
            $pelatihanSkala = Pelatihan_Skala::select('skala_id', 'nama')->get();

            // Pastikan data dikembalikan dalam format yang sesuai untuk DataTables
            return response()->json(['data' => $pelatihanSkala]);
        }

        return view('pelatihan.skala.index');
    }

    public function create()
    {
        return view('pelatihan.skala.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'skala_id' => 'required|string|max:255|unique:pelatihan_skalas',
            'nama' => 'required|string|max:255',
        ]);

        Pelatihan_Skala::create([
            'skala_id' => $request->skala_id,
            'nama' => $request->nama,
            'created_at' => now(),
            'updated_at' => null,
        ]);

        return redirect()->route('pelatihan-skala.index')->with('success', 'Pelatihan Skala berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pelatihanSkala = Pelatihan_Skala::findOrFail($id);
        return view('pelatihan.skala.edit', compact('pelatihanSkala'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'skala_id' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
        ]);

        $pelatihanSkala = Pelatihan_Skala::findOrFail($id);

        $pelatihanSkala->update([
            'nama' => $request->nama,
            'updated_at' => now(),
        ]);

        return redirect()->route('pelatihan-skala.index')->with('success', 'Pelatihan Skala berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelatihanSkala = Pelatihan_Skala::findOrFail($id);
        $pelatihanSkala->delete();

        return redirect()->route('pelatihan-skala.index')->with('success', 'Pelatihan Skala berhasil dihapus!');
    }

    public function hapusTerpilih(Request $request)
    {
        $ids = $request->ids;
        Pelatihan_Skala::whereIn('skala_id', $ids)->delete();

        return response()->json(['success' => "Pelatihan Skala terpilih berhasil dihapus."]);
    }
}
