<?php

namespace App\Imports\Pelatihan;

use App\Models\Pelatihan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets; // Tambahkan ini untuk menggunakan multiple sheets
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Mendefinisikan logika import data dari file Excel.
 */
class PelatihanImpor implements ToCollection, WithHeadingRow, WithValidation, WithMultipleSheets
{
    use Importable;

    /**
     * Proses setiap baris data sebagai Collection.
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Tentukan nilai tna_id
            $tna_id = $row['tna_id'] ?? null;

            // Cek dan format kategori_id menjadi string dan trim spasi ekstra
            $kategori_id = isset($row['kategori_id']) ? trim(strval($row['kategori_id'])) : null;

            // Jika kategori_id kosong, set null
            if ($kategori_id === '') {
                $kategori_id = null;
            }

            // Cek dan perbaiki nilai jumlah_peserta (integer)
            $jumlah_peserta = isset($row['jumlah_peserta']) && is_numeric($row['jumlah_peserta'])
                ? (int)$row['jumlah_peserta']  // Pastikan integer
                : 0;

            // Cek dan perbaiki nilai durasi (decimal)
            $durasi = $this->convertToDecimal($row['durasi']);  // Panggil fungsi convertToDecimal

            // Cek dan perbaiki nilai rangking (integer)
            $rangking = isset($row['rangking']) && is_numeric($row['rangking'])
                ? (int)$row['rangking']  // Pastikan integer
                : 0;

            // Cek jika kolom nama kosong, set null
            $nama = $row['nama'] ?? null;
            if (empty($nama)) {
                $nama = null;  // Bisa juga set ke nilai default lainnya, jika diperlukan
            }

            // Cek jika kolom instansi_id kosong, set null
            $instansi_id = isset($row['instansi_id']) ? $row['instansi_id'] : null;

            // Update atau buat data pelatihan baru
            Pelatihan::updateOrCreate(
                [
                    'pelatihan_id' => $row['pelatihan_id'],
                ],
                [
                    'nip'                => $row['nip'],
                    'skala_id'           => $row['skala_id'],
                    'bentuk_id'          => $row['bentuk_id'],
                    'kategori_id'        => $kategori_id,
                    'jenis_id'           => $row['jenis_id'],
                    'tna_id'             => $tna_id,
                    'kode_pelatihan'     => $row['kode_pelatihan'],
                    'nama'               => $nama,  // Pastikan nama bisa null
                    'tgl_mulai'          => $row['tgl_mulai'] ?? null,
                    'tgl_selesai'        => $row['tgl_selesai'] ?? null,
                    'durasi'             => $durasi,  // Simpan sebagai float (desimal)
                    'jumlah_peserta'     => $jumlah_peserta,  // Simpan sebagai integer
                    'rangking'           => $rangking,  // Simpan sebagai integer
                    'nomor_sertifikat'   => $row['nomor_sertifikat'] ?? null,
                    'link_bukti_dukung'  => $row['link_bukti_dukung'] ?? null,
                    'instansi_id'        => $instansi_id,  // Instansi ID bisa null
                    'created_at'         => $row['created_at'],
                    'updated_at'         => $row['created_at'],
                ]
            );
        }
    }

    /**
     * Fungsi untuk mengonversi durasi menjadi angka desimal.
     * Memastikan bahwa jika ada koma, diganti dengan titik.
     *
     * @param mixed $value
     * @return float|null
     */
    private function convertToDecimal($value)
    {
        // Pastikan nilai tidak kosong atau NULL
        if (empty($value)) {
            return 0.0;  // Mengembalikan 0.0 jika durasi kosong
        }

        // Hapus spasi tambahan
        $value = trim($value);

        // Ganti koma dengan titik jika ada
        $value = str_replace(',', '.', $value);

        // Pastikan nilai yang diberikan bisa dikonversi ke angka
        if (is_numeric($value)) {
            return floatval($value);  // Konversi ke float agar bisa menjadi angka desimal
        }

        // Jika tidak valid, kembalikan 0.0
        return 0.0;  // Kembalikan nilai default 0.0 jika invalid
    }

    /**
     * Format tanggal untuk memastikan formatnya sesuai (misalnya Y-m-d).
     * @param string|null $tanggal
     * @return string|null
     */
    private function formatTanggal($tanggal)
    {
        if (empty($tanggal)) {
            return null;
        }

        try {
            return \Carbon\Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Aturan validasi untuk setiap baris data yang diimpor.
     * @return array
     */
    public function rules(): array
    {
        return [
            'tna_id' => ['nullable', 'string', 'max:27'],
            'nip' => ['required', 'string', 'max:20'],
            'kode_pelatihan' => ['required', 'string', 'max:2'],
            'nama' => ['nullable', 'string', 'max:255'],  // Perubahan di sini, jadi nullable
            'durasi' => ['nullable', 'numeric'],
            'jumlah_peserta' => ['nullable', 'numeric'],
            'rangking' => ['nullable', 'numeric'],
            'nomor_sertifikat' => ['nullable', 'string'],
            'link_bukti_dukung' => ['nullable', 'string'],
            'instansi_id' => ['nullable', 'integer'],  // Perubahan di sini, jadi nullable
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
            'kode_pelatihan.required' => 'Kolom Kode Pelatihan wajib diisi.',
            'nama.required' => 'Kolom Nama wajib diisi.',
            'instansi_id.required' => 'Kolom Instansi ID wajib diisi.',
        ];
    }

    /**
     * Menentukan sheet yang akan digunakan.
     *
     * @param \Maatwebsite\Excel\Concerns\Importable $import
     * @return array
     */
    public function sheets(): array
    {
        // Mengambil sheet dengan nama 'hasil_pelatihan'
        return [
            'data_pelatihan' => $this,  // Menunjukkan sheet tertentu berdasarkan nama
        ];
    }
}
