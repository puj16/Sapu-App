<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PetaniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run()
    {
        $namaList = [
            'Zahfa', 'Yira', 'Tasha', 'Gem Anata', 'Ruma',
            // 'Fajar', 'Gita', 'Hari', 'Indah', 'Joko',
            // 'Kiki', 'Lia', 'Miko', 'Nina', 'Oki',
            // 'Putri', 'Rudi', 'Sinta', 'Tomi', 'Uli'
        ];

        shuffle($namaList); // Acak nama
        $now = Carbon::now(); // Dapatkan waktu saat ini
        $dateString = $now->format('YmdH'); // Format YmdH

        $data = [];

        for ($i = 0; $i < 5; $i++) {
            $nik = '360801' . str_pad((string)rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
            $wa = '0831' . str_pad((string)rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
            $nama = $namaList[$i];

            $data[] = [
                'nik' => $nik,
                'nama' => $nama,
                'wa' => $wa,
                'kk' => $nik . '_kk_' . $dateString . '.jpg',
                'ktp' => $nik . '_ktp_' . $dateString . '.jpg',
                'password' => Hash::make('123456'),
                'remember_token' => Str::random(60),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('petani')->insert($data);
    }
}
