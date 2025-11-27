<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'satkers';

    // Menentukan primary key
    protected $primaryKey = 'satker_id'; // satker_id sebagai primary key

    // Menonaktifkan auto increment karena menggunakan satker_id sebagai primary key
    public $incrementing = false; // Karena satker_id bukan auto increment

    // Tipe data primary key
    protected $keyType = 'string';  // karena satker_id adalah string

    // Kolom yang dapat diisi
    protected $fillable = [
        'satker_id',
        'instansi_id',
        'negara_id',
        'provinsi_id',
        'kabupaten_id',
        'nama',
        'keterangan'
    ];


    // Relasi ke Instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id', 'instansi_id');
    }
    // Relasi ke WilayahNegara
    public function negara()
    {
        return $this->belongsTo(Wilayah_Negara::class, 'negara_id', 'negara_id');
    }

    // Relasi ke WilayahProvinsi
    public function provinsi()
    {
        return $this->belongsTo(Wilayah_Provinsi::class, 'provinsi_id', 'provinsi_id');
    }

    // Relasi ke WilayahKabupaten
    public function kabupaten()
    {
        return $this->belongsTo(Wilayah_Kabupaten::class, 'kabupaten_id', 'kabupaten_id');
    }

    // Pada model Satker.php
    public function pelatihanRekapCapaian()
    {
        return $this->hasMany(Pelatihan_Rekap_Capaian::class, 'satker_id', 'satker_id');
    }
}
