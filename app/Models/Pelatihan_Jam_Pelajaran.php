<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatihan_Jam_Pelajaran extends Model
{
    protected $table = 'pelatihan_jam_pelajarans';

    protected $fillable = [
        'asynchronous',
        'synchronous',
        'jam_pelajaran',

    ];
}
