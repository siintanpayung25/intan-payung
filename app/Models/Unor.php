<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unor extends Model
{
    // Nama tabel sudah sesuai konvensi Laravel, tapi didefinisikan untuk kejelasan.
    protected $table = 'unors';

    // Primary Key yang digunakan (sesuai migrasi)
    protected $primaryKey = 'unor_id';

    // Primary Key non-incrementing dan bertipe string (sesuai migrasi)
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'unor_id',
        'flag',
        'nama',
        'singkatan',
        'lingkup',
        'eselon_id',
    ];

    /**
     * Relasi ke Model Jabatan_Eselon (sesuai Foreign Key di migrasi).
     */
    public function eselon()
    {
        // Model yang dituju adalah Jabatan_Eselon
        return $this->belongsTo(Jabatan_Eselon::class, 'eselon_id', 'eselon_id');
    }

    // --- Relasi Balik untuk Kepentingan Rekap ---

    /**
     * Relasi balik ke Pelatihan_Rekap_Durasi.
     */
    public function rekapDurasi()
    {
        // Menggunakan Model yang sudah disepakati
        return $this->hasMany(Pelatihan_Rekap_Durasi::class, 'unor_id', 'unor_id');
    }
}
