<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Pelatihan;
use App\Models\Pelatihan_Rekap_Capaian;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon; // Import Carbon untuk waktu

class RekapDurasiPelatihanCommand extends Command
{
    /**
     * Nama dan signature command.
     */
    protected $signature = 'rekap:pelatihan-durasi {tahun?}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Mengagregasi data durasi pelatihan pegawai ke tabel rekap tahunan.';

    /**
     * Jalankan command.
     */
    public function handle()
    {
        $targetYear = $this->argument('tahun') ?? date('Y');
        $this->info("Memulai proses rekap durasi untuk tahun {$targetYear}...");

        try {
            // --- KOREKSI KRUSIAL: Menambahkan JOIN ke tabel pegawais untuk mendapatkan unor_id dan satker_id ---
            $rekapData = DB::table('pelatihans')
                // Asumsi: nip adalah kunci penghubung ke tabel pegawais
                ->join('pegawais', 'pelatihans.nip', '=', 'pegawais.nip')
                ->select([
                    'pelatihans.nip',
                    // Mengambil unor_id dan satker_id dari tabel pegawais
                    'pegawais.unor_id',
                    'pegawais.satker_id',
                    // Menggunakan kolom durasi dan tna_id (sesuai konfirmasi terakhir)
                    DB::raw('SUM(CASE WHEN pelatihans.tna_id IS NOT NULL THEN pelatihans.durasi ELSE 0 END) as durasi_tna'),
                    DB::raw('SUM(CASE WHEN pelatihans.tna_id IS NULL THEN pelatihans.durasi ELSE 0 END) as durasi_non_tna'),
                ])
                // KOREKSI: Menggunakan tgl_mulai (sesuai Controller) dan menambahkan alias tabel
                ->whereYear('pelatihans.tgl_mulai', $targetYear)
                ->whereNotNull('pelatihans.nip')
                // Mengelompokkan berdasarkan kolom dari kedua tabel
                ->groupBy('pelatihans.nip', 'pegawais.unor_id', 'pegawais.satker_id')
                ->get();
        } catch (\Exception $e) {
            // Jika terjadi error SQL, tampilkan di console Artisan dan log.
            $this->error("Gagal saat agregasi data sumber: " . $e->getMessage());
            Log::error("REKAP ERROR: Gagal agregasi data sumber (Tahun {$targetYear})", ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }

        // DEBUG 1: Cek data sumber
        Log::info('DEBUG REKAP: Data Sumber Pelatihan (Count=' . $rekapData->count() . ')', [
            'Target Year' => $targetYear,
            'Source Data Count' => $rekapData->count(),
            'First 5 Records' => $rekapData->take(5)->toArray()
        ]);


        $upsertArray = $rekapData->map(function ($item) use ($targetYear) {
            $durasiTotal = $item->durasi_tna + $item->durasi_non_tna;
            $targetJam = 20; // Target default jam setahun
            $persentase = ($durasiTotal / $targetJam) * 100;

            return [
                // DIKEMBALIKAN: pelatihan_rekap_capaian_id harus ada nilainya karena tidak memiliki default value di DB
                'pelatihan_rekap_capaian_id' => $item->nip . '-' . $targetYear,
                'nip' => $item->nip,
                'tahun' => $targetYear,
                'unor_id' => $item->unor_id,
                'satker_id' => $item->satker_id,
                'target_jam_setahun' => $targetJam,
                'durasi_tna' => $item->durasi_tna,
                'durasi_non_tna' => $item->durasi_non_tna,
                'durasi_total' => $durasiTotal,
                'persentase_capaian' => round($persentase, 2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        })->toArray();

        // DEBUG 2: Cek data siap simpan
        $totalProcessed = count($upsertArray);

        if ($totalProcessed > 0) {
            Log::info('DEBUG REKAP: Data Siap Upsert (Count=' . $totalProcessed . ')', [
                'Upsert Array Count' => $totalProcessed,
                'First 5 Upsert Records' => array_slice($upsertArray, 0, 5)
            ]);

            // Lakukan Upsert (Insert/Update)
            // Menggunakan ['nip', 'tahun'] sebagai kunci unik komposit
            Pelatihan_Rekap_Capaian::upsert(
                $upsertArray,
                ['nip', 'tahun'], // Kunci unik untuk UPDATE/INSERT
                ['unor_id', 'satker_id', 'target_jam_setahun', 'durasi_tna', 'durasi_non_tna', 'durasi_total', 'persentase_capaian', 'updated_at']
            );
        } else {
            Log::warning("DEBUG REKAP: Upsert array kosong untuk tahun {$targetYear}. Tidak ada data yang akan disimpan.");
        }

        $this->info("Sinkronisasi Selesai! Total {$totalProcessed} data rekap berhasil diperbarui/dibuat untuk tahun {$targetYear}.");

        return Command::SUCCESS;
    }
}
