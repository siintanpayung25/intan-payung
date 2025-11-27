<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pangkat_JenisKenaikanPangkat extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'jenis_kenaikan_pangkats';

    // Menentukan kolom-kolom yang dapat diisi
    protected $fillable = ['jenis_kp_id', 'nama'];

    // Menentukan kolom primary key
    protected $primaryKey = 'jenis_kp_id';

    // Menentukan bahwa 'jenis_kp_id' adalah string
    protected $keyType = 'string';

    // Menonaktifkan auto-increment untuk 'jenis_kp_id'
    public $incrementing = false;

    // Fungsi untuk menampilkan nama jenis kenaikan pangkat
    public function __toString()
    {
        return $this->nama;
    }
}
