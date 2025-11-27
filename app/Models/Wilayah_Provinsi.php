<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah_Provinsi extends Model
{
    use HasFactory;

    protected $table = 'wilayah_provinsis'; // Nama tabel

    // Kolom-kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'provinsi_id',
        'negara_id',
        'kode_provinsi',
        'nama',
        'ibukota'
    ];

    // Tentukan primary key
    protected $primaryKey = 'provinsi_id'; // Ganti dengan nama kolom yang sesuai

    // Tentukan tipe primary key
    protected $keyType = 'string'; // Ganti dengan 'int' jika primary key menggunakan tipe data integer

    // Relasi dengan Wilayah_Negara
    public function negara()
    {
        return $this->belongsTo(Wilayah_Negara::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kabupaten
    public function kabupatens()
    {
        return $this->hasMany(Wilayah_Kabupaten::class, 'provinsi_id', 'provinsi_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kecamatan
    public function kecamatans()
    {
        return $this->hasMany(Wilayah_Kecamatan::class, 'provinsi_id', 'provinsi_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Desa
    public function desas()
    {
        return $this->hasMany(Wilayah_Desa::class, 'provinsi_id', 'provinsi_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }
}
