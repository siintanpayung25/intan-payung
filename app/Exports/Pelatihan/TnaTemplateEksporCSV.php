<?php

namespace App\Exports\Pelatihan;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TnaTemplateEksporCSV implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    /**
     * Menyediakan data untuk ekspor (template kosong)
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Data template kosong untuk CSV
        return collect([
            ['0123456789012345680101-2024', '012345678901234567', '01', '01', 'Pelatihan Manajemen', 'Deskripsi pelatihan', '2024', '0', '2024-09-01 15:09:36', '2024-09-10 10:21:26'],
            ['0123456789012345670202-2025', '012345678901234567', '02', '02', 'Pelatihan Teknologi', 'Deskripsi lainnya', '2025', '1', '2025-10-01 11:21:31', '2025-10-02 08:42:13'],
        ]);
    }

    /**
     * Menyediakan judul kolom di CSV
     * @return array
     */
    public function headings(): array
    {
        return [
            'tna_id',
            'nip',
            'sifat_tna_id',
            'kode_tna',
            'nama',
            'deskripsi',
            'tahun',
            'status_tna',
            'created_at',
            'updated_at',
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
