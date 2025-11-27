<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPegawai extends Model
{
    use HasFactory;

    // Tentukan nama tabel (default Laravel: status_pegawais)
    protected $table = 'status_pegawais';

     // Tentukan kolom yang bisa diisi (fillable)
    protected $fillable = [
        'status_pegawai_id',
        'nama',
    ];

    // Tentukan primary key
    protected $primaryKey = 'status_pegawai_id';

    // Tentukan tipe data primary key
    protected $keyType = 'string';

    // Tentukan apakah primary key auto increment
    public $incrementing = false;

    // Fungsi untuk menampilkan nama status pegawai
    public function __toString()
    {
        return $this->nama;
    }
}
