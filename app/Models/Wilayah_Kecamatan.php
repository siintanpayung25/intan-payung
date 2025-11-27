<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah_Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'wilayah_kecamatans'; // Nama tabel

    // Kolom-kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'kecamatan_id',
        'negara_id',
        'provinsi_id',
        'kabupaten_id', 
        'kode_kecamatan', 
        'nama'
    ];

    // Tentukan primary key
    protected $primaryKey = 'kecamatan_id'; // Ganti dengan nama kolom yang sesuai

    // Tentukan tipe primary key
    protected $keyType = 'string';  // Ganti dengan 'int' jika primary key menggunakan tipe data integer

    // Relasi dengan Wilayah_Negara
    public function negara()
    {
        return $this->belongsTo(Wilayah_Negara::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Wilayah_Provinsi::class, 'provinsi_id', 'provinsi_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kabupaten
    public function kabupaten()
    {
        return $this->belongsTo(Wilayah_Kabupaten::class, 'kabupaten_id', 'kabupaten_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Desa
    public function desas()
    {
        return $this->hasMany(Wilayah_Desa::class, 'kecamatan_id', 'kecamatan_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }
}
