<?php

namespace App\Exports\Pelatihan;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class PelatihanTemplateEksporCSV implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    /**
     * Menyediakan data untuk ekspor (template kosong)
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Data template kosong untuk CSV
        return collect([
            ['012345678901234567', '012345678901234567-1110101-2025-06-11-01', '1', '1', '101', '01', '0123456789012345670101-2024', '01', 'Public speaking',  '2024-06-11', '2024-06-12', '1.5', '25', '2', '123/BANGKOM/2565/BPS/A/2024', 'http://sertifikat_pelatihan_2024', '101', '2024-09-01 15:09:36', '2024-09-10 10:21:26'],
            ['001234567890123456', '001234567890123456-2210202-2025-10-22-02', '2', '2', '102', '02', '0012345678901234560202-2025', '02', 'Manajemen SDM',  '2025-10-22', '2025-10-23', '4', '40', '1', '123456/serf-mandiri/Agustus 2025', 'https://drive.google.com/file/d/sdm', '107', '2025-10-01 11:21:31', '2025-10-02 08:42:13'],
        ]);
    }

    /**
     * Menyediakan judul kolom di CSV
     * @return array
     */
    public function headings(): array
    {
        return [
            'pelatihan_id',       // ID Pelatihan (gabungan kode)
            'nip',                // NIP Pegawai (Relasi ke tabel pegawais)
            'skala_id',           // Foreign Key ke PelatihanSkala
            'bentuk_id',          // Foreign Key ke PelatihanBentuk
            'kategori_id',        // Foreign Key ke PelatihanKategori
            'jenis_id',           // Foreign Key ke PelatihanJenis
            'tna_id',             // Foreign Key ke PelatihanTna (optional)
            'kode_pelatihan',     // Kode Pelatihan (2 karakter)
            'nama',               // Nama Pelatihan
            'tgl_mulai',          // Tanggal Mulai (nullable)
            'tgl_selesai',        // Tanggal Selesai (nullable)
            'durasi',             // Durasi pelatihan (format jam:menit)
            'jumlah_peserta',     // Jumlah Peserta
            'rangking',           // Rangking
            'nomor_sertifikat',   // Nomor sertifikat
            'link_bukti_dukung',  // Link Bukti Dukung
            'instansi_id',        // Foreign Key ke Instansi
        ];
    }

    /**
     * Menyediakan pengaturan CSV seperti delimiter, enclosure, dll.
     * @return array
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',  // Menetapkan delimiter untuk CSV
            'enclosure' => '"',  // Menetapkan enclosure untuk CSV
            'line_ending' => "\r\n", // Menetapkan line ending
        ];
    }
}
