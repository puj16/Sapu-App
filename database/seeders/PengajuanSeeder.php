<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lahan;
use App\Models\Pengajuan;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua lahan yang add_by TIDAK diawali dengan '350617560104'
        $lahanList = Lahan::whereRaw("add_by NOT LIKE '350617560104%'")
                          ->orderBy('add_by')
                          ->get();

        // Kelompokkan berdasarkan add_by
        $grouped = $lahanList->groupBy('add_by');

        foreach ($grouped as $addBy => $lahanPerPetani) {
            if ($lahanPerPetani->count() >= 2) {
                // Ambil dua lahan pertama untuk komoditi padi dan tebu
                $lahan1 = $lahanPerPetani[0];
                $lahan2 = $lahanPerPetani[1];

                Pengajuan::create([
                    'NOP'              => $lahan1->NOP,
                    'nik'              => $addBy,
                    'luasan'           => $lahan1->Luas,
                    'status_validasi'  => 1,
                    'tahun'            => '2024',
                    'komoditi'         => 'Padi',
                ]);

                Pengajuan::create([
                    'NOP'              => $lahan2->NOP,
                    'nik'              => $addBy,
                    'luasan'           => $lahan2->Luas,
                    'status_validasi'  => 1,
                    'tahun'            => '2024',
                    'komoditi'         => 'Tebu',
                ]);
            }
        }
    }
}
