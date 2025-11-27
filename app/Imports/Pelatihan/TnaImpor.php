<?php

namespace App\Imports\Pelatihan;

use App\Models\Pelatihan_Tna;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str; // Untuk generate ID atau UUID jika diperlukan

/**
 * Mendefinisikan logika import data dari file Excel.
 */
class TnaImpor implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * Proses setiap baris data sebagai Collection.
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Pastikan kolom yang diperlukan ada dan tidak kosong
            if (isset($row['nip']) && isset($row['kode_tna']) && isset($row['nama'])) {
                Pelatihan_Tna::create([
                    'tna_id' => $row['tna_id'], // TNA ID, jika diimpor
                    'nip' => $row['nip'],
                    'sifat_tna_id' => $row['sifat_tna_id'] ?? '01', // default jika tidak ada
                    'kode_tna' => $row['kode_tna'],
                    'nama' => $row['nama'],
                    'deskripsi' => $row['deskripsi'] ?? null,
                    'tahun' => $row['tahun'] ?? date('Y'), // Default tahun jika tidak ada
                    'status_tna' => $row['status_tna'] ?? false, // Defaultkan status TNA jika tidak ada
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                ]);
            }
        }
    }

    /**
     * Aturan validasi untuk setiap baris data yang diimpor.
     * @return array
     */
    public function rules(): array
    {
        return [
            'tna_id' => ['required', 'string', 'max:27'],
            'nip' => ['required', 'string', 'max:20'], // Pastikan NIP ada di tabel pegawais
            'sifat_tna_id' => ['nullable', 'string', 'max:2', 'exists:pelatihan_sifat_tna,sifat_tna_id'],
            'kode_tna' => ['required', 'string', 'max:2'], // Kode TNA 2 karakter
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:150'], // Deskripsi opsional
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'status_tna' => ['nullable', 'boolean'], // Pastikan status_tna berupa boolean
        ];
    }

    /**
     * Pesan kustom untuk validasi.
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nip.required' => 'Kolom NIP wajib diisi.',
            'nip.exists' => 'NIP (:input) tidak ditemukan di database Pegawai.',
            'kode_tna.required' => 'Kolom Kode TNA wajib diisi.',
            'sifat_tna_id.exists' => 'ID Sifat TNA yang dimasukkan tidak valid.',
        ];
    }
}
