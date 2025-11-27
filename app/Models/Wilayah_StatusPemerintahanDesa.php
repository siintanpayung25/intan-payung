<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah_StatusPemerintahanDesa extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model
    protected $table = 'wilayah_status_pemerintahan_desas'; 

     // Kolom-kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'status_pemdesa_id',  // ID status pemerintahan desa
        'nama',               // Nama status pemerintahan desa
    ];

    // Menentukan kolom primary key dan tipe data
    protected $primaryKey = 'status_pemdesa_id'; // Kolom primary key
    public $incrementing = false; // Karena menggunakan 'status_pemdesa_id' sebagai string, bukan auto-incrementing
    protected $keyType = 'string'; // Tipe primary key adalah string

    // Mutator untuk menampilkan nama dengan format tertentu
    public function __toString()
    {
        return $this->nama;
    }
}
