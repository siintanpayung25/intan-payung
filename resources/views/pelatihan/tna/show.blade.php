@extends('layouts.dasar')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Data Training Needs Analysis (TNA)</h5>
                    <a href="{{ route('pelatihan.tna.index') }}" class="btn btn-sm btn-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body p-4">

                    <div class="row">
                        {{-- Blok Informasi Pegawai & TNA --}}
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Informasi Dasar</h6>
                            <hr class="mt-0">

                            {{-- NIP / Pegawai --}}
                            <p class="mb-2">
                                {{-- Judul di bold menggunakan <strong> --}}
                                    <span class="font-weight-bold d-block text-sm"><strong>NIP /
                                            Pegawai:</strong></span>
                                    {{ $pelatihan_tna->pegawai ? $pelatihan_tna->pegawai->nip . ' - ' .
                                    $pelatihan_tna->pegawai->nama : '-' }}
                            </p>

                            {{-- Kebutuhan Pelatihan --}}
                            <p class="mb-2">
                                {{-- Judul di bold menggunakan <strong> --}}
                                    <span class="font-weight-bold d-block text-sm"><strong>Kebutuhan
                                            Pelatihan:</strong></span>
                                    {{ $pelatihan_tna->nama ?? '-' }}
                            </p>

                            {{-- Tahun --}}
                            <p class="mb-2">
                                {{-- Judul di bold menggunakan <strong> --}}
                                    <span class="font-weight-bold d-block text-sm"><strong>Tahun TNA:</strong></span>
                                    {{ $pelatihan_tna->tahun ?? '-' }}
                            </p>
                        </div>

                        {{-- Blok Status & Prioritas --}}
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Prioritas & Status</h6>
                            <hr class="mt-0">

                            {{-- Sifat TNA --}}
                            <p class="mb-2">
                                {{-- Judul di bold menggunakan <strong> --}}
                                    <span class="font-weight-bold d-block text-sm"><strong>Sifat TNA:</strong></span>
                                    {{ $pelatihan_tna->sifatTna ? $pelatihan_tna->sifatTna->nama : '-' }}
                            </p>

                            {{-- Status Pelaksanaan --}}
                            <p class="mb-2">
                                @php
                                $statusValue = $pelatihan_tna->status_tna;
                                $label = ($statusValue == 1) ? 'Sudah Dilaksanakan' : 'Belum Dilaksanakan';
                                $class = ($statusValue == 1) ? 'bg-success' : 'bg-warning';
                                @endphp
                                {{-- Judul di bold menggunakan <strong> --}}
                                    <span class="font-weight-bold d-block text-sm"><strong>Status
                                            Pelaksanaan:</strong></span>
                                    <span class="badge {{ $class }}">{{ $label }}</span>
                            </p>

                            {{-- Tgl Dibuat --}}
                            <p class="mb-2">
                                {{-- Judul di bold menggunakan <strong> --}}
                                    <span class="font-weight-bold d-block text-sm"><strong>Dibuat Pada:</strong></span>
                                    {{ $pelatihan_tna->created_at ? $pelatihan_tna->created_at->format('d M Y, H:i') :
                                    '-' }}
                            </p>

                            {{-- Tgl Diubah --}}
                            <p class="mb-2">
                                {{-- Judul di bold menggunakan <strong> --}}
                                    <span class="font-weight-bold d-block text-sm"><strong>Diperbarui
                                            Pada:</strong></span>
                                    {{ $pelatihan_tna->updated_at ? $pelatihan_tna->updated_at->format('d M Y, H:i') :
                                    '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Blok Keterangan --}}
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Deskripsi</h6>
                            <hr class="mt-0">
                            <p class="text-sm">
                                {{ $pelatihan_tna->deskripsi ?? 'Tidak ada deskripsi pelatihan.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end mt-4">
                        {{-- KONDISI HAPUS: Hanya izinkan hapus jika status_tna BUKAN 1 (Belum Dilaksanakan) --}}
                        @if($pelatihan_tna->status_tna == 1)
                        {{-- Nonaktifkan tombol hapus jika status = 1 (Sudah Dilaksanakan) --}}
                        <button class="btn btn-danger me-2" disabled
                            title="Data TNA yang sudah dilaksanakan tidak dapat dihapus.">
                            Hapus Data (Nonaktif)
                        </button>
                        <a href="{{ route('pelatihan.tna.edit', $pelatihan_tna->tna_id) }}" class="btn btn-warning">Ubah
                            Data</a>
                        @else
                        {{-- Tombol Hapus: Aktif jika status BUKAN 1. Gunakan modal kustom. --}}
                        <button class="btn btn-danger me-2" onclick="showDeleteModal('{{ $pelatihan_tna->tna_id }}')">
                            Hapus Data
                        </button>
                        <a href="{{ route('pelatihan.tna.edit', $pelatihan_tna->tna_id) }}" class="btn btn-warning">Ubah
                            Data</a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- 1. Formulir Tersembunyi untuk Aksi Hapus (Digunakan oleh confirmDelete) --}}
<form id="delete-form-{{ $pelatihan_tna->tna_id }}"
    action="{{ route('pelatihan.tna.destroy', $pelatihan_tna->tna_id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- 2. HTML untuk Modal Konfirmasi Kustom (Pengganti alert/confirm) --}}
{{-- Modal ini secara eksplisit diatur display: none agar tidak muncul saat page load --}}
<div id="custom-delete-modal" class="position-fixed top-0 start-0 w-100 h-100"
    style="background-color: rgba(0, 0, 0, 0.5); z-index: 1050; display: none;">
    <div class="card p-4 shadow-lg" style="width: 90%; max-width: 400px; margin: auto;">
        <h5 class="card-title text-danger">Konfirmasi Hapus Data</h5>
        <p class="card-text mb-4">Apakah Anda *yakin* ingin menghapus data Training Needs Analysis ini? Karena akan
            dihapus permanen.</p>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger me-2" id="confirm-delete-button">Ya, Hapus</button>
            <button type="button" class="btn btn-secondary" onclick="hideDeleteModal()">Batal</button>
        </div>
    </div>
</div>

<script>
    // Variabel global untuk menyimpan ID TNA yang akan dihapus
    let tnaIdToDelete = null;

    // Fungsi untuk menampilkan modal konfirmasi kustom
    // Modal akan ditampilkan dengan display: flex untuk mengaktifkan centering
    function showDeleteModal(tnaId) {
        tnaIdToDelete = tnaId;
        const modal = document.getElementById('custom-delete-modal');
        // Tampilkan modal dan terapkan centering
        modal.style.display = 'flex';
        modal.style.justifyContent = 'center';
        modal.style.alignItems = 'center';
    }

    // Fungsi untuk menyembunyikan modal konfirmasi kustom
    function hideDeleteModal() {
        tnaIdToDelete = null;
        const modal = document.getElementById('custom-delete-modal');
        modal.style.display = 'none'; // Sembunyikan modal
    }

    // Fungsi tunggal yang dipanggil saat tombol "Ya, Hapus" di modal diklik
    function confirmDelete() {
        if (tnaIdToDelete) {
            const form = document.getElementById('delete-form-' + tnaIdToDelete);
            if (form) {
                // Sembunyikan modal sebelum submit
                hideDeleteModal();
                // Kirim form DELETE
                form.submit();
            } else {
                console.error('Formulir hapus tidak ditemukan untuk ID:', tnaIdToDelete);
                hideDeleteModal();
            }
        } else {
            hideDeleteModal();
        }
    }

    // Kaitkan fungsi confirmDelete ke tombol konfirmasi di modal setelah DOM dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const confirmButton = document.getElementById('confirm-delete-button');
        if (confirmButton) {
            confirmButton.onclick = confirmDelete;
        }
        
        // PENGAMAN: Pastikan modal tersembunyi saat dimuat (untuk mengatasi potensi bug rendering)
        hideDeleteModal();
    });

</script>
@endsection