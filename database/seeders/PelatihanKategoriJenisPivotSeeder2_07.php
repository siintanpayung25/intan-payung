<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelatihanKategoriJenisPivotSeeder2_07 extends Seeder
{
    public function run()
    {
        // Mendapatkan tanggal dan waktu sekarang untuk created_at
        $createdAt = Carbon::now();

        // Data kategori dan jenis untuk insert ke dalam pivot
        $data = [
            ['kategori_id' => '222', 'jenis_id' => '09'],
            ['kategori_id' => '222', 'jenis_id' => '10'],
            ['kategori_id' => '222', 'jenis_id' => '11'],
            ['kategori_id' => '222', 'jenis_id' => '12'],
            ['kategori_id' => '222', 'jenis_id' => '13'],
            ['kategori_id' => '222', 'jenis_id' => '14'],
            ['kategori_id' => '222', 'jenis_id' => '15'],
            ['kategori_id' => '222', 'jenis_id' => '16'],
            ['kategori_id' => '222', 'jenis_id' => '17'],
            ['kategori_id' => '222', 'jenis_id' => '18'],
            ['kategori_id' => '222', 'jenis_id' => '19'],
            ['kategori_id' => '222', 'jenis_id' => '20'],
            ['kategori_id' => '223', 'jenis_id' => '28'],
            // Tambahkan data lainnya sesuai dengan kebutuhan
        ];

        foreach ($data as $item) {
            DB::table('pelatihan_kategori_jenis_pivot')->insert([
                'kategori_jenis_id' => $item['kategori_id'] . $item['jenis_id'], // Gabungkan kategori_id dan jenis_id
                'kategori_id' => $item['kategori_id'],
                'jenis_id' => $item['jenis_id'],
                'created_at' => $createdAt,
                'updated_at' => null, // Kolom updated_at tetap null
            ]);
        }
    }
}
