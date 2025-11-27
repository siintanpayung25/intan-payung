<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah_Kabupaten extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'wilayah_kabupatens'; // Nama tabel

    // Kolom-kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'kabupaten_id',
        'negara_id',
        'provinsi_id',
        'status_adminkab_id',
        'kode_kabupaten',
        'nama',
        'ibukota'
    ];

    // Tentukan primary key
    protected $primaryKey = 'kabupaten_id'; // Ganti dengan nama kolom yang sesuai

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

    // Relasi dengan wilayah_status_adm_kabupaten
    public function status_adm_kabupaten()
    {
        return $this->belongsTo(Wilayah_StatusAdministratifKabupaten::class, 'status_adminkab_id', 'status_adminkab_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kecamatan
    public function kecamatans()
    {
        return $this->hasMany(Wilayah_Kecamatan::class, 'kabupaten_id', 'kabupaten_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Desa
    public function desas()
    {
        return $this->hasMany(Wilayah_Desa::class, 'kabupaten_id', 'kabupaten_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }
}
