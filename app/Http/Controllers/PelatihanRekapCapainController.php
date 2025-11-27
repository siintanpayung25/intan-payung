<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Wilayah_Provinsi;
use App\Models\Satker;
use App\Models\Unor;
use App\Models\Pelatihan_Rekap_Capaian;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Exports\Pelatihan\DurasiCapaianExport;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\BufferedOutput;


// Mengasumsikan BaseController ada di namespace ini atau di-import
class PelatihanRekapCapainController extends BaseController
{
    /**
     * Tampilan utama (dengan filter dan data untuk dropdown)
     */
    public function index(Request $request)
    {
        // Ambil provinsi_id dari session (sudah dihitung dan disimpan di BaseController)
        $userProvinsiId = session('provinsi_id');

        // --- 1. LOGIKA TAHUN (Tidak Berubah) ---
        $startYears = Pelatihan::distinct()->selectRaw('YEAR(tgl_mulai) as year')->whereNotNull('tgl_mulai')->pluck('year')->toArray();
        $endYears = Pelatihan::distinct()->selectRaw('YEAR(tgl_selesai) as year')->whereNotNull('tgl_selesai')->pluck('year')->toArray();
        $allYears = array_unique(array_merge($startYears, $endYears));
        rsort($allYears);
        if (empty($allYears)) {
            $allYears[] = date('Y');
        }
        $selectedYear = $request->input('tahun_rekap', $allYears[0]);

        // --- 2. LOGIKA PROVINSI (Disesuaikan) ---
        $provinsiQuery = Wilayah_Provinsi::orderBy('nama', 'asc')->select('provinsi_id', 'nama');

        // Filter daftar provinsi: Jika user memiliki provinsi_id, hanya tampilkan provinsi mereka.
        if ($userProvinsiId) {
            $provinsiQuery->where('provinsi_id', $userProvinsiId);
        }

        $provinsis = $provinsiQuery->get();

        // Tentukan ID Provinsi yang Terpilih (selectedProvinsiId):
        // 1. Cek Request (jika ada filter yang dikirim user).
        // 2. Jika Request kosong, gunakan ID Provinsi dari user yang login (sebagai default).
        $selectedProvinsiId = $request->input('provinsi_id');
        if (!$selectedProvinsiId && $userProvinsiId) {
            $selectedProvinsiId = $userProvinsiId;
        }
        // Jika user adalah Admin/Super Admin, $selectedProvinsiId akan tetap null (memungkinkan pilihan "-- Pilih Provinsi --")

        // --- 3. LOGIKA Satker (FILTER BERJENJANG) ---
        $satkers = collect();
        $selectedSatkerId = $request->input('satker_id');

        // Gunakan $selectedProvinsiId (yang sudah otomatis terisi jika user login sebagai user daerah)
        if ($selectedProvinsiId) {
            $satkers = Satker::where('provinsi_id', $selectedProvinsiId)
                ->orderBy('nama', 'asc')
                ->get(['satker_id', 'nama']);
        }

        if ($selectedSatkerId && $satkers->isEmpty()) {
            $selectedSatkerId = null;
        }

        // --- 4. LOGIKA UNOR ---
        $unors = Unor::orderBy('singkatan', 'asc')->get(['unor_id', 'singkatan', 'nama']);
        $selectedUnorId = $request->input('unor_id');

        // --- 5. KIRIM DATA KE VIEW ---
        return view('pelatihan.pelatihan.rekap_capaian', [
            'years' => $allYears,
            'selectedYear' => $selectedYear,
            'provinsis' => $provinsis,
            'selectedProvinsiId' => $selectedProvinsiId, // Variabel ini sekarang bisa menampung ID user
            'satkers' => $satkers,
            'selectedSatkerId' => $selectedSatkerId,
            'unors' => $unors,
            'selectedUnorId' => $selectedUnorId,
        ]);
    }

    // placeholder CRUD methods
    public function create()
    { /* ... */
    }
    public function store(Request $request)
    { /* ... */
    }
    public function show(string $id)
    { /* ... */
    }
    public function edit(string $id)
    { /* ... */
    }
    public function update(Request $request, string $id)
    { /* ... */
    }
    public function destroy(string $id)
    { /* ... */
    }

    // ----------------------------------------------------------------------
    // HELPER METHOD: Digunakan oleh Datatable dan Export
    // ----------------------------------------------------------------------

    /**
     * Membangun query dasar dengan semua filter yang diterapkan.
     */
    private function buildQuery(Request $request)
    {
        $tahun = $request->input('tahun_rekap') ?? $request->input('tahun');
        $provinsiId = $request->input('provinsi_id');
        $satkerId = $request->input('satker_id');
        $unorId = $request->input('unor_id');

        if (!$tahun) {
            $tahun = date('Y');
        }

        $query = Pelatihan_Rekap_Capaian::query()
            ->with(['pegawai', 'unor', 'satker']);

        // **1. FILTER TAHUN** (Wajib)
        $query->where('tahun', $tahun);

        // **2. FILTER UNOR**
        if ($unorId) {
            $query->where('unor_id', $unorId);
        }

        // **3. FILTER SATKER**
        if ($satkerId) {
            $query->where('satker_id', $satkerId);
        }

        // **4. FILTER PROVINSI** (Hanya jika Satker tidak dipilih)
        elseif ($provinsiId) {
            $query->where('satker_id', 'like', $provinsiId . '%');
        }

        return $query;
    }


    // ----------------------------------------------------------------------
    // DATATABLE SERVER-SIDE PROCESSING (Route: pelatihan.rekap_durasi.data)
    // ----------------------------------------------------------------------

    /**
     * Endpoint untuk DataTables AJAX, menyediakan data rekapitulasi.
     */
    public function getDataForDatatable(Request $request)
    {
        $query = $this->buildQuery($request);

        return Datatables::of($query)
            ->addColumn('nama_pegawai', function (Pelatihan_Rekap_Capaian $rekap) {
                // Pastikan akses ke relasi pegawai
                return $rekap->pegawai->nama ?? 'N/A';
            })
            ->editColumn('nip', function (Pelatihan_Rekap_Capaian $rekap) {
                // Pastikan NIP diambil dari relasi pegawai
                return $rekap->pegawai->nip ?? $rekap->nip ?? 'N/A';
            })
            ->addColumn('nama_unor', function (Pelatihan_Rekap_Capaian $rekap) {
                return $rekap->unor->nama ?? 'N/A';
            })
            ->addColumn('nama_satker', function (Pelatihan_Rekap_Capaian $rekap) {
                return $rekap->satker->nama ?? 'N/A';
            })
            ->editColumn('durasi_tna', function (Pelatihan_Rekap_Capaian $rekap) {
                return number_format($rekap->durasi_tna, 2);
            })
            ->editColumn('durasi_non_tna', function (Pelatihan_Rekap_Capaian $rekap) {
                return number_format($rekap->durasi_non_tna, 2);
            })
            ->editColumn('durasi_total', function (Pelatihan_Rekap_Capaian $rekap) {
                return number_format($rekap->durasi_total, 2) . ' Jam';
            })
            ->editColumn('persentase_capaian', function (Pelatihan_Rekap_Capaian $rekap) {
                return number_format($rekap->persentase_capaian, 2) . '%';
            })
            ->editColumn('target_jam_setahun', function (Pelatihan_Rekap_Capaian $rekap) {
                return number_format($rekap->target_jam_setahun, 0);
            })
            ->rawColumns(['nama_pegawai', 'nama_unor', 'nama_satker'])
            ->make(true);
    }

    // ----------------------------------------------------------------------
    // AKSI: SINKRONISASI DATA (Route: pelatihan.rekap_durasi.sinkronisasi)
    // ----------------------------------------------------------------------

    /**
     * Method untuk memicu rekapitulasi data (dipanggil via AJAX Sinkronisasi)
     */
    public function pemicuRekap(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        Log::info('DEBUG CONTROLLER: Memulai pemicu Rekap untuk tahun ' . $tahun);

        $output = new BufferedOutput;

        try {
            $exitCode = Artisan::call('rekap:pelatihan-durasi', ['tahun' => $tahun], $output);
            $artisanOutput = $output->fetch();
        } catch (\Exception $e) {
            Log::error('DEBUG CONTROLLER: Artisan Call Exception', [
                'Command' => 'rekap:pelatihan-durasi',
                'Error' => $e->getMessage(),
                'File' => $e->getFile() . ':' . $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => "Proses rekapitulasi gagal secara fatal: " . $e->getMessage()
            ], 500);
        }

        Log::info('DEBUG CONTROLLER: Artisan Call Result', [
            'Command' => 'rekap:pelatihan-durasi',
            'Exit Code' => $exitCode,
            'Output' => trim($artisanOutput)
        ]);

        if ($exitCode !== 0) {
            return response()->json([
                'success' => false,
                'message' => "Proses rekapitulasi gagal (Kode {$exitCode}). Pesan: " . trim($artisanOutput)
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Proses rekapitulasi data durasi pelatihan tahun {$tahun} telah selesai. Silakan klik 'Tampilkan Data' untuk memuat hasilnya."
        ]);
    }

    // ----------------------------------------------------------------------
    // AKSI: EKSPOR DATA (Route: pelatihan.rekap_durasi.export)
    // ----------------------------------------------------------------------

    /**
     * Menangani proses ekspor ke Excel/PDF.
     */
    public function exportData(Request $request)
    {
        $format = $request->input('format', 'xlsx');
        $tahun = $request->input('tahun_rekap') ?? date('Y');

        // **MODIFIKASI: Tambahkan timestamp (tanggal dan jam) pada nama file**
        $timestamp = date('Ymd_His');
        $fileName = "Rekap_Durasi_Pelatihan_{$tahun}_{$timestamp}.xlsx";

        // 1. Ambil Data Export dari Query Helper
        $query = $this->buildQuery($request);
        $dataToExport = $query->get();

        // 2. Panggil Export Class
        if ($format === 'xlsx') {
            // Panggil class export
            return Excel::download(new DurasiCapaianExport($dataToExport), $fileName);
        }

        return back()->with('error', 'Format ekspor tidak valid.');
    }
}
