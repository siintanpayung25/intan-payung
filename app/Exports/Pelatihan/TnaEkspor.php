<?php

namespace App\Exports\Pelatihan;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Mendefinisikan format data yang akan diekspor ke file Excel.
 * Menggunakan WithColumnFormatting untuk memastikan NIP disimpan sebagai Teks.
 */
class TnaEkspor implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $dataToExport;

    public function __construct(Collection $dataToExport)
    {
        // Data yang sudah diformat (melalui join, dll) dari Controller
        $this->dataToExport = $dataToExport;
    }

    /**
     * Memformat data dari Collection. NIP dikonversi ke string dan 
     * ditambahkan Zero Width Space (\u{200b}) untuk memaksa Excel 
     * memperlakukannya sebagai teks, menjaga leading zeros.
     * * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->dataToExport->map(function ($tna) {
            // Pastikan NIP dikonversi ke string
            $nip = (string) ($tna->nip ?? 'N/A');

            // Tambahkan karakter Zero Width Space (\u{200b}) untuk memaksa NIP sebagai Teks
            if ($nip !== 'N/A' && !empty($nip)) {
                $nip = "\u{200b}" . $nip;
            }

            // Urutan array harus sesuai dengan headings()
            return [
                'nip' => $nip, // Kolom A (NIP)
                'nama_pegawai' => $tna->nama_pegawai ?? 'N/A', // Kolom B
                'kebutuhan_pelatihan' => $tna->kebutuhan_pelatihan ?? 'N/A', // Kolom C
                'sifat_tna' => $tna->sifat_tna ?? 'N/A', // Kolom D
                'tahun' => $tna->tahun ?? 'N/A', // Kolom E
                'deskripsi' => $tna->deskripsi ?? 'N/A', // Kolom F
                'status' => $tna->status_text ?? 'N/A', // Kolom G
            ];
        });
    }

    /**
     * Header kolom di Excel.
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP Pegawai',
            'Nama Pegawai',
            'Kebutuhan Pelatihan',
            'Sifat TNA',
            'Tahun',
            'Deskripsi',
            'Status',
        ];
    }

    /**
     * Menerapkan format khusus untuk setiap kolom di Excel.
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            // Kolom A (NIP) diatur sebagai Teks ('@')
            'A' => '@',
        ];
    }
}
