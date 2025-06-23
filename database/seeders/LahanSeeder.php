<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LahanSeeder extends Seeder
{
    public function run()
    {
        $statusList = ['Milik', 'Sewa', 'Garapan', 'Bagi Hasil'];
        $dateString = Carbon::now()->format('YmdH');

        // Ambil semua nik petani
        $petaniList = DB::table('petani')->pluck('nik')->toArray();
        $petaniSudahAdaLahan = DB::table('lahan')->pluck('add_by')->unique()->toArray();
        $petaniBelumAdaLahan = array_diff($petaniList, $petaniSudahAdaLahan);

        foreach ($petaniBelumAdaLahan as $addByNik) {
            // Record 1: nik = add_by (lahan pribadi)
            $nop1 = self::generateNop();
            DB::table('lahan')->insert([
                'NOP' => $nop1,
                'NIK' => $addByNik,
                'Luas' => self::randomLuas(),
                'status' => $statusList[array_rand($statusList)],
                'Foto_SPPT' => $nop1 . '_sppt_' . $dateString . '.jpg',
                'add_by' => $addByNik,
            ]);

            // Record 2: nik random ≠ add_by (lahan dikelola petani lain)
            $nikLain = self::randomOtherNik($petaniList, $addByNik);
            $nop2 = self::generateNop();
            DB::table('lahan')->insert([
                'NOP' => $nop2,
                'NIK' => $nikLain,
                'Luas' => self::randomLuas(),
                'status' => $statusList[array_rand($statusList)],
                'Foto_SPPT' => $nop2 . '_sppt_' . $dateString . '.jpg',
                'add_by' => $addByNik,
            ]);
        }
    }

    public static function generateNop(): string
    {
        return str_pad(strval(random_int(1, 999999999999999999)), 18, '0', STR_PAD_LEFT);
    }

    private static function randomLuas()
    {
        return round(mt_rand(10, 200) / 100, 2); // 0.10 - 2.00
    }

    private static function randomOtherNik(array $list, $exclude)
    {
        $filtered = array_values(array_filter($list, fn($nik) => $nik !== $exclude));
        return $filtered[array_rand($filtered)];
    }
}
