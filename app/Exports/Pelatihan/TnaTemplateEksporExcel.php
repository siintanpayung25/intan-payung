<?php

namespace App\Exports\Pelatihan;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TnaTemplateEksporExcel implements FromCollection, WithHeadings
{
    /**
     * Menyediakan data untuk ekspor (template kosong)
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Data template kosong, seperti contoh sebelumnya
        return collect([
            ['0123456789012345680101-2024', '012345678901234567', '01', '01', 'Pelatihan Manajemen', 'Deskripsi pelatihan', '2024', '0', '2024-09-01 15:09:36', '2024-09-10 10:21:26'],
            ['0123456789012345670202-2025', '012345678901234567', '02', '02', 'Pelatihan Teknologi', 'Deskripsi lainnya', '2025', '1', '2025-10-01 11:21:31', '2025-10-02 08:42:13'],
        ]);
    }

    /**
     * Menyediakan judul kolom di Excel
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
}
