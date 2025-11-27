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
class PelatihanBackup implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $dataToBackup;

    public function __construct(Collection $dataToBackup)
    {
        // Data yang sudah diformat (melalui join, dll) dari Controller
        $this->dataToBackup = $dataToBackup;
    }

    /**
     * Memformat data dari Collection. NIP dikonversi ke string dan 
     * ditambahkan Zero Width Space (\u{200b}) untuk memaksa Excel 
     * memperlakukannya sebagai teks, menjaga leading zeros.
     * * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->dataToBackup->map(function ($tna) {
            // Pastikan NIP dikonversi ke string
            $nip = (string) ($tna->nip ?? 'N/A');

            // Tambahkan karakter Zero Width Space (\u{200b}) untuk memaksa NIP sebagai Teks
            if ($nip !== 'N/A' && !empty($nip)) {
                $nip = "\u{200b}" . $nip;
            }

            // Pastikan status_tna diproses dengan benar (mencegah status '0' hilang)
            $status_tna = $tna->status_tna ?? 'N/A'; // Jika null, gunakan 'N/A' sebagai default

            // Urutan array harus sesuai dengan headings()
            return [
                'tna_id' => $tna->tna_id,        // Kolom A
                'nip' => $nip,                   // Kolom B
                'sifat_tna_id' => $tna->sifat_tna_id, // Kolom C
                'kode_tna' => $tna->kode_tna,     // Kolom D
                'nama' => $tna->nama,             // Kolom E
                'deskripsi' => $tna->deskripsi,   // Kolom F
                'tahun' => $tna->tahun,           // Kolom G
                'status_tna' => (string) $status_tna, // Kolom H
                'created_at' => $tna->created_at, // Kolom I
                'updated_at' => $tna->updated_at, // Kolom J
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
