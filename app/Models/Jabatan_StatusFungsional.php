<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan_StatusFungsional extends Model
{
    use HasFactory;

    // Menentukan nama tabel
    protected $table = 'jabatan_status_fungsionals';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'status_fungsional_id', // ID Status Fungsional
        'status',               // Status Fungsional
    ];

    // Kolom primary key
    protected $primaryKey = 'status_fungsional_id';

    // Mengatur tipe data primary key sebagai string
    protected $keyType = 'string';

    /**
     * Menyimpan status fungsional
     * sebagai representasi objek.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->status;
    }
}
