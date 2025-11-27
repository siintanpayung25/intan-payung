<?php

namespace App\Exports\Pelatihan;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DurasiCapaianExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $dataToExport;

    public function __construct(Collection $dataToExport)
    {
        $this->dataToExport = $dataToExport;
    }

    /**
     * Memformat data dari Collection. NIP dikonversi ke string.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->dataToExport->map(function ($rekap) {
            // 1. Ambil NIP, pastikan bertipe string. 
            $nip = (string) ($rekap->pegawai->nip ?? $rekap->nip ?? 'N/A');
            $targetJam = (int) ($rekap->target_jam_setahun ?? 0);

            // ==========================================================
            // *** SOLUSI TERKUAT: TAMBAHKAN KARAKTER TAK TERLIHAT (ZERO WIDTH SPACE) ***
            // Karakter \u{200b} memaksa data menjadi non-numerik, dan 
            // format '@' di bawah akan menyimpannya sebagai Teks.
            // ==========================================================
            if ($nip !== 'N/A' && !empty($nip)) {
                $nip = "\u{200b}" . $nip; // Tambahkan karakter Zero Width Space
            }

            // Urutan harus sesuai dengan headings()
            return [
                'nip' => $nip, // Kolom A (String yang dipaksa non-numerik)
                'nama_pegawai' => $rekap->pegawai->nama ?? 'N/A', // Kolom B
                'nama_unor' => $rekap->unor->nama ?? 'N/A', // Kolom C
                'nama_kabupaten' => $rekap->kabupaten->nama ?? 'N/A', // Kolom D
                'durasi_tna' => number_format($rekap->durasi_tna, 2, '.', ''), // Kolom E
                'durasi_non_tna' => number_format($rekap->durasi_non_tna, 2, '.', ''), // Kolom F
                'durasi_total' => number_format($rekap->durasi_total, 2, '.', ''), // Kolom G
                'target_jam' => $targetJam, // Kolom H
                'persentase_capaian' => number_format($rekap->persentase_capaian, 2, '.', ''), // Kolom I
            ];
        });
    }

    /**
     * Header kolom di Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama Pegawai',
            'UNOR',
            'Kabupaten',
            'Durasi TNA (Jam)',
            'Durasi Non-TNA (Jam)',
            'TOTAL Capaian (Jam)',
            'Target Jam',
            '% Capaian',
        ];
    }

    /**
     * Menerapkan format khusus untuk setiap kolom di Excel.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            // MENGGUNAKAN RAW FORMAT STRING '@' (Teks)
            'A' => '@',

            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
