<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan_Bentuk;
use App\Models\Pelatihan_Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PelatihanBentukController extends BaseController
{
    // Menampilkan daftar Pelatihan Bentuk
    public function index()
    {
        $pelatihanBentuk = Pelatihan_Bentuk::all(); // Ambil semua data Pelatihan Bentuk
        return view('pelatihan.bentuk.index', compact('pelatihanBentuk'));
    }

    // Menampilkan form untuk menambahkan Pelatihan Bentuk
    public function create()
    {
        return view('pelatihan.bentuk.create');
    }

    // Menyimpan Pelatihan Bentuk yang baru
    public function store(Request $request)
    {
        $request->validate([
            'bentuk_id' => 'required|string|max:255|unique:pelatihan_bentuks',
            'nama' => 'required|string|max:255',
        ]);

        Pelatihan_Bentuk::create([
            'bentuk_id' => $request->bentuk_id,
            'nama' => $request->nama,
        ]);

        return redirect()->route('pelatihan-bentuk.index')->with('success', 'Pelatihan Bentuk berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit Pelatihan Bentuk
    public function edit($id)
    {
        $pelatihanBentuk = Pelatihan_Bentuk::findOrFail($id);
        return view('pelatihan.bentuk.edit', compact('pelatihanBentuk'));
    }

    // Memperbarui data Pelatihan Bentuk
    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Cari Pelatihan Bentuk berdasarkan ID
        $pelatihanBentuk = Pelatihan_Bentuk::findOrFail($id);

        // Update data, hanya kolom yang dibutuhkan
        $pelatihanBentuk->update([
            'nama' => $request->nama,  // Update nama
            // Anda bisa menambahkan kolom lain jika diperlukan
        ]);

        // Redirect kembali ke index dengan pesan sukses
        return redirect()->route('pelatihan-bentuk.index')->with('success', 'Pelatihan Bentuk berhasil diperbarui!');
    }

    // Menghapus Pelatihan Bentuk
    public function destroy($id)
    {
        $pelatihanBentuk = Pelatihan_Bentuk::findOrFail($id);
        $pelatihanBentuk->delete();

        return redirect()->route('pelatihan-bentuk.index')->with('success', 'Pelatihan Bentuk berhasil dihapus!');
    }

    // Menghapus data Pelatihan Bentuk yang dipilih
    public function hapusTerpilih(Request $request)
    {
        $ids = $request->ids;
        Pelatihan_Bentuk::whereIn('bentuk_id', $ids)->delete();

        return response()->json(['success' => "Pelatihan Bentuk terpilih berhasil dihapus."]);
    }

    // Endpoint untuk memuat kategori berdasarkan bentuk_id
    public function getKategorisByBentuk($bentuk_id)
    {
        $kategoris = Pelatihan_Kategori::where('bentuk_id', $bentuk_id)->get();

        return response()->json($kategoris);
    }
}
