<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data dummy untuk user
        // DB::table('users')->insert([
        //     'nip' => '197111111992011004', // NIP sebagai primary key
        //     'username' => 'amrizal',
        //     'email' => 'amrizalut@gmail.com',
        //     'password' => Hash::make('amri123'), // Password yang sudah di-hash
        //     'remember_token' => null,
        //     'created_at' => now(),
        //     'updated_at' => null, // Mengosongkan updated_at
        // ]);

        // DB::table('users')->insert([
        //     'nip' => '197905012000122002',
        //     'username' => 'meita',
        //     'email' => 'meita@bps.go.id',
        //     'password' => Hash::make('meita123'),
        //     'remember_token' => null,
        //     'created_at' => now(),
        //     'updated_at' => null, // Mengosongkan updated_at
        // ]);

        DB::table('users')->insert([
            'nip' => '198403142007011001',
            'username' => 'joni',
            'email' => 'joni@bps.go.id',
            'password' => Hash::make('joni123'),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => null, // Mengosongkan updated_at
        ]);
    }
}
