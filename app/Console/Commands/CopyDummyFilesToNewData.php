<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CopyDummyFilesToNewData extends Command
{
    protected $signature = 'dummy:copy-to-new';
    protected $description = 'Copy dummy ktp, kk, sppt for newly seeded petani and lahan';

    public function handle()
    {
        $now = date('YmdH');
        $publicDisk = Storage::disk('public');

        // Paths to dummy files
        $dummyKtp = File::files(public_path('assets/dummy/ktp'));
        $dummyKk = File::files(public_path('assets/dummy/kk'));
        $dummySppt = File::files(public_path('assets/dummy/sppt'));

        // Handle petani
        $newPetani = DB::table('petani')
            ->whereDate('created_at', today())
            ->get();

        foreach ($newPetani as $petani) {
            $nik = $petani->nik;

            // Copy KTP
            $randomKtp = $dummyKtp[array_rand($dummyKtp)];
            $ktpName = "{$nik}_ktp_{$now}." . $randomKtp->getExtension();
            $publicDisk->put("assets/doc/ktp/{$ktpName}", File::get($randomKtp));

            // Copy KK
            $randomKk = $dummyKk[array_rand($dummyKk)];
            $kkName = "{$nik}_kk_{$now}." . $randomKk->getExtension();
            $publicDisk->put("assets/doc/kk/{$kkName}", File::get($randomKk));

            // Update database
            DB::table('petani')->where('nik', $nik)->update([
                'ktp' => $ktpName,
                'kk' => $kkName,
            ]);

            $this->info("Petani {$nik} -> ktp & kk copied");
        }

        // Handle lahan
        $newLahan = DB::table('lahan')->get();

        foreach ($newLahan as $lahan) {
            $nop = $lahan->NOP;

            $randomSppt = $dummySppt[array_rand($dummySppt)];
            $spptName = "{$nop}_sppt_{$now}." . $randomSppt->getExtension();
            $publicDisk->put("assets/doc/sppt/{$spptName}", File::get($randomSppt));

            DB::table('lahan')->where('NOP', $nop)->update([
                'Foto_SPPT' => $spptName,
            ]);

            $this->info("Lahan {$nop} -> SPPT copied");
        }

        $this->info('Selesai menyalin semua file dummy ke data baru.');
    }
}
