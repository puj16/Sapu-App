<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengajuan;
use App\Models\Rdkk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RDKK1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $pengajuanList = Pengajuan::where('komoditi', 'Padi')
                ->where('status_validasi', 1)
                ->get();

            foreach ($pengajuanList as $pengajuan) {
                $luasan = $pengajuan->luasan;

                foreach ([1, 2,3] as $idPupuk) {
                    $harga = DB::table('pupuk')->where('id', $idPupuk)->value('harga');
                    
                    $volume_mt1 = 0;
                    if ( $idPupuk === 1 ) {
                        $volume_mt1 = $luasan*250;
                    }elseif ( $idPupuk === 2 ) {
                        $volume_mt1=$luasan*275;
                    }else {
                        $volume_mt1=$luasan*500;
                    }

                    $totalVolume = $volume_mt1;
                    $totalHarga = $totalVolume * $harga;

                Rdkk::create([
                    'tahun' => 2025,
                    'periode' => 1,
                    'komoditi' => 'Padi',
                    'nik' => $pengajuan->nik,
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_pupuk' => $idPupuk,
                    'volume_pupuk_mt1' => $volume_mt1,
                    'volume_pupuk_mt2' => 0,
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
