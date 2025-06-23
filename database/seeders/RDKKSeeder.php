<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengajuan;
use App\Models\Rdkk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RDKKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $pengajuanList = Pengajuan::where('komoditi', 'Tebu')
                ->where('nik', 'not like', '350617560104%')
                ->get();

            foreach ($pengajuanList as $pengajuan) {
                $luasan = $pengajuan->luasan;

                foreach ([1, 2] as $idPupuk) {
                    $harga = DB::table('pupuk')->where('id', $idPupuk)->value('harga');
                    
                    $volume_mt2 = $idPupuk === 1 
                        ? $luasan * 44 
                        : $luasan * 66;

                    $totalVolume = $volume_mt2;
                    $totalHarga = $totalVolume * $harga;

                Rdkk::create([
                    'tahun' => 2025,
                    'periode' => 1,
                    'komoditi' => 'Tebu',
                    'nik' => $pengajuan->nik,
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_pupuk' => $idPupuk,
                    'volume_pupuk_mt1' => 0,
                    'volume_pupuk_mt2' => $volume_mt2,
                    'volume_pupuk_mt3' => 0,
                    'total_harga' => $totalHarga,
                    'status_penyaluran' => 0,
                    'info' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
