<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'pelatihans';

    // Menentukan primary key yang digunakan
    protected $primaryKey = 'pelatihan_id';

    // Menonaktifkan auto increment karena kita menggunakan pelatihan_id sebagai primary key
    public $incrementing = false;

    // Menentukan tipe data primary key
    protected $keyType = 'string';  // karena pelatihan_id adalah string

    // Kolom yang dapat diisi
    protected $fillable = [
        'pelatihan_id',
        'nip',
        'skala_id',
        'bentuk_id',
        'kategori_id',
        'jenis_id',
        'tna_id',
        'kode_pelatihan',
        'nama',
        'tgl_mulai',
        'tgl_selesai',
        'durasi',
        'instansi_id',
        'jumlah_peserta',
        'rangking',
        'link_bukti_dukung',
        'nomor_sertifikat',
    ];

    // Relasi ke Pegawai (NIP)
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }

    // Relasi ke PelatihanSkala
    public function skala()
    {
        return $this->belongsTo(Pelatihan_Skala::class, 'skala_id', 'skala_id');
    }

    // Relasi ke PelatihanBentuk
    public function bentuk()
    {
        return $this->belongsTo(Pelatihan_Bentuk::class, 'bentuk_id', 'bentuk_id');
    }

    // Relasi ke PelatihanKategori
    public function kategori()
    {
        return $this->belongsTo(Pelatihan_Kategori::class, 'kategori_id', 'kategori_id');
    }

    // Relasi ke PelatihanJenis
    public function jenis()
    {
        return $this->belongsTo(Pelatihan_Jenis::class, 'jenis_id', 'jenis_id');
    }

    // Relasi ke PelatihanTna (Menambahkan relasi baru untuk tna_id)
    public function tna()
    {
        return $this->belongsTo(Pelatihan_Tna::class, 'tna_id', 'tna_id');
    }

    // Relasi ke Instansi

    public function getDetailInstansiAttribute()
    {
        // Coba cari di Instansi
        $instansi = Instansi::where('instansi_id', $this->instansi_id)->first();

        if ($instansi) {
            return $instansi;
        }

        // Jika tidak ditemukan, coba cari di Universitas
        $universitas = Universitas::where('instansi_id', $this->instansi_id)->first();

        return $universitas;
    }

    // Relasi baru untuk pencarian Instansi (digunakan oleh orWhereHas)
    public function instansis()
    {
        // Menggunakan belongsTo karena pelatihan memiliki instansi_id yang merujuk ke tabel instansis
        return $this->belongsTo(Instansi::class, 'instansi_id', 'instansi_id');
    }

    // Relasi baru untuk pencarian Universitas (digunakan oleh orWhereHas)
    public function universitas()
    {
        // Menggunakan belongsTo karena pelatihan memiliki instansi_id yang merujuk ke tabel universitas
        return $this->belongsTo(Universitas::class, 'instansi_id', 'instansi_id');
    }
}
