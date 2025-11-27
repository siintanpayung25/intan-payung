<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pangkat_Golongan extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'pangkat_golongans'; 

    // Menentukan kolom-kolom yang dapat diisi
    protected $fillable = [
        'golongan_id', // Primary Key
        'golongan', 
        'ruang', 
        'gol_ruang', 
        'pangkat'
    ];

    // Menentukan kolom primary key
    protected $primaryKey = 'golongan_id';

    // Menentukan bahwa 'golongan_id' adalah string
    protected $keyType = 'string';

    // Menonaktifkan auto-increment untuk 'golongan_id'
    public $incrementing = false;

    // Mengatur agar kolom gol_ruang disimpan sesuai dengan format yang diminta
    public static function boot()
    {
        parent::boot();

        static::saving(function ($golongan) {
            // Menghitung nilai gol_ruang berdasarkan golongan dan ruang
            $golongan->gol_ruang = "{$golongan->golongan}/{$golongan->ruang}";
        });
    }

    // Fungsi untuk menampilkan gol_ruang saat dipanggil
    public function __toString()
    {
        return $this->gol_ruang;
    }
}
