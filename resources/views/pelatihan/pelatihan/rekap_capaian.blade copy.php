@extends('layouts.dasar')
@section('title', 'Rekap Capaian Durasi Pelatihan')

@section('content')

<main class="container mx-auto p-4 md:p-8">
    <h1 class="text-3xl font-extrabold mb-6 text-gray-800">Rekap Capaian Durasi Pelatihan Pegawai</h1>

    <!-- Card Utama untuk Filter dan Aksi Data -->
    <div class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-gray-100">
        <h2 class="text-xl font-bold mb-4 text-blue-700 border-b pb-2">Filter Data Rekap</h2>

        {{-- Form Filter menggunakan method GET agar parameter tetap ada di URL --}}
        <form id="filter-form" class="space-y-4" method="GET" action="{{ route('pelatihan-rekap-capaian.index') }}">

            {{-- BARIS FILTER UTAMA: Tahun, Provinsi, Kabupaten, UNOR --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Dropdown Tahun Rekap -->
                <div>
                    <label for="tahun_rekap" class="block text-sm font-medium text-gray-700">Tahun Rekap</label>
                    <select id="tahun_rekap" name="tahun_rekap"
                        class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        @foreach ($years as $year)
                        <option value="{{ $year }}" {{ $year==$selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Provinsi -->
                <div>
                    <label for="provinsi_id" class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <select id="provinsi_id" name="provinsi_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        <option value="">-- Pilih Semua Provinsi --</option>
                        @foreach ($provinsis as $provinsi)
                        <option value="{{ $provinsi->provinsi_id }}" {{ $provinsi->provinsi_id == $selectedProvinsiId ?
                            'selected' : '' }}>
                            {{ $provinsi->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Kabupaten (DINAMIS - Berjenjang) -->
                <div>
                    <label for="kabupaten_id" class="block text-sm font-medium text-gray-700">Kabupaten</label>
                    <select id="kabupaten_id" name="kabupaten_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out {{ $selectedProvinsiId ? '' : 'bg-gray-50' }}">
                        <option value="">-- Pilih Semua Kabupaten --</option>
                        {{-- Loop data kabupaten yang sudah difilter oleh Controller --}}
                        @foreach ($kabupatens as $kabupaten)
                        <option value="{{ $kabupaten->kabupaten_id }}" {{ $kabupaten->kabupaten_id ==
                            $selectedKabupatenId ? 'selected' : '' }}>
                            {{ $kabupaten->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Unor -->
                <div>
                    <label for="unor_id" class="block text-sm font-medium text-gray-700">Unit Organisasi (UNOR)</label>
                    <select id="unor_id" name="unor_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        <option value="">-- Semua UNOR --</option>
                        @foreach ($unors as $unor)
                        <option value="{{ $unor->unor_id }}" {{ $unor->unor_id == $selectedUnorId ? 'selected' : '' }}>
                            {{ $unor->singkatan }} - {{ $unor->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tombol "Tampilkan Data" dipindahkan di luar grid filter --}}
        </form>

        <!-- Tombol Aksi -->
        <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-gray-100">
            <button onclick="document.getElementById('filter-form').submit()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Tampilkan Data
            </button>
            <button
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0v10a8.001 8.001 0 0015.356-2M20 12h.01">
                    </path>
                </svg>
                Sinkronisasi Data Rekap
            </button>
            <button
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m-3-3h6m-7 1v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                Ekspor Excel
            </button>
        </div>
    </div>

    <!-- Area Tabel Hasil Rekap -->
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold mb-4 text-blue-700 border-b pb-2">Hasil Rekap Durasi</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
                {{-- Header Tabel --}}
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Pegawai</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            UNOR</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kabupaten</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Durasi TNA (Jam)</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Durasi Non-TNA (Jam)</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            TOTAL Capaian (Jam)</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Target (Jam)</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">%
                            Capaian</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Di sini tempat Looping Data Rekap Pegawai --}}
                    <tr>
                        <td colspan="9" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                            Masukkan filter dan klik "Tampilkan Data" untuk memuat hasil rekap.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination Placeholder --}}
        <div class="mt-6 flex justify-between items-center text-sm text-gray-600">
            <span>Menampilkan 0 dari 0 entri</span>
            <div class="space-x-2">
                <button
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 disabled:opacity-50 hover:bg-gray-100 transition duration-150"
                    disabled>Previous</button>
                <button
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 disabled:opacity-50 hover:bg-gray-100 transition duration-150"
                    disabled>Next</button>
            </div>
        </div>
    </div>
</main>

<script>
    // SCRIPT JAVASCRIPT UNTUK MEMICU REFRESH/FORM SUBMIT
    // Memastikan Kabupaten terfilter saat Provinsi berubah (berjenjang sisi server)
    
    document.addEventListener('DOMContentLoaded', function () {
        const provinsiDropdown = document.getElementById('provinsi_id');
        const filterForm = document.getElementById('filter-form');
        
        // Ketika Provinsi diubah, kirimkan form untuk mendapatkan daftar Kabupaten yang baru
        provinsiDropdown.addEventListener('change', function() {
            // Kita reset Kabupaten ID menjadi kosong sebelum kirim form
            // Ini mencegah pengiriman ID Kabupaten lama yang mungkin tidak valid di Provinsi baru
            document.getElementById('kabupaten_id').value = ''; 
            filterForm.submit();
        });

        // Opsi: Kirim form saat Kabupaten atau Tahun juga diubah, agar data rekap langsung muncul.
        document.getElementById('kabupaten_id').addEventListener('change', function() {
            filterForm.submit();
        });
        document.getElementById('unor_id').addEventListener('change', function() {
            filterForm.submit();
        });
        document.getElementById('tahun_rekap').addEventListener('change', function() {
            filterForm.submit();
        });
    });
</script>
@endsection