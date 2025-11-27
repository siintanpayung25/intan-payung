<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah_Negara extends Model
{
    use HasFactory;

    protected $table = 'wilayah_negaras'; // Nama tabel

    // Kolom-kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'negara_id',
        'nama', 
        'ibukota'
    ];

    // Tentukan primary key
    protected $primaryKey = 'negara_id'; // Ganti dengan nama kolom yang sesuai

    // Tentukan tipe primary key
    protected $keyType = 'string'; // Ganti dengan 'int' jika primary key menggunakan tipe data integer

    // Relasi dengan Wilayah_Provinsi
    public function provinsis()
    {
        return $this->hasMany(Wilayah_Provinsi::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kabupaten
    public function kabupatens()
    {
        return $this->hasMany(Wilayah_Kabupaten::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Kecamatan
    public function kecamatans()
    {
        return $this->hasMany(Wilayah_Kecamatan::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }

    // Relasi dengan Wilayah_Desa
    public function desas()
    {
        return $this->hasMany(Wilayah_Desa::class, 'negara_id', 'negara_id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    }
}
