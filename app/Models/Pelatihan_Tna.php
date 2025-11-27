<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan_Tna extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'pelatihan_tna';

    // Menentukan primary key
    protected $primaryKey = 'tna_id';

    // Menonaktifkan auto increment karena menggunakan tna_id sebagai primary key
    public $incrementing = false;

    // Tipe data primary key
    protected $keyType = 'string';  // karena tna_id adalah string

    // Kolom yang dapat diisi
    protected $fillable = [
        'tna_id',
        'nip',
        'sifat_tna_id',
        'kode_tna',
        'nama',
        'deskripsi',
        'tahun',
        'status_tna'
    ];

    // Relasi ke Pegawai (NIP)
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }

    // Relasi ke PelatihanSifatTna
    public function sifatTna()
    {
        return $this->belongsTo(Pelatihan_Sifat_Tna::class, 'sifat_tna_id', 'sifat_tna_id');
    }

    // Relasi ke Pelatihan (Tabel pelatihan yang berisi tna_id)
    public function pelatihan()
    {
        return $this->hasMany(Pelatihan::class, 'tna_id', 'tna_id');
    }
}
