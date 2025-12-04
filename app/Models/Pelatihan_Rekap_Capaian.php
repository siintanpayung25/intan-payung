<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatihan_Rekap_Capaian extends Model
{
    // Nama tabel yang benar di database
    protected $table = 'pelatihan_rekap_capaian';

    // Mendefinisikan Primary Key yang digunakan
    protected $primaryKey = 'pelatihan_rekap_capaian_id';

    // Primary Key non-incrementing dan bertipe string
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'pelatihan_rekap_capaian_id',
        'nip',
        'tahun',
        'unor_id',
        'satker_id',
        'target_jam_setahun',
        'durasi_tna',
        'durasi_non_tna',
        'durasi_total',
        'persentase_capaian',
    ];

    /**
     * Relasi ke Model Pegawai (untuk mendapatkan nama).
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }

    /**
     * Relasi ke Model Unor (untuk mendapatkan nama Unit Organisasi).
     */
    public function unor()
    {
        return $this->belongsTo(Unor::class, 'unor_id', 'unor_id');
    }

    /**
     * Relasi ke Model Satker (untuk mendapatkan nama satker).
     */
    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_id', 'satker_id');
    }
}
