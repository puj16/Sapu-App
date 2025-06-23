<?php

namespace App\Exports;

use App\Models\Rdkk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class RdkkExport implements FromCollection, WithHeadings, WithMapping
{
    protected $groupedData;
    protected $uniquePupuk;

    public function __construct($groupedData, $uniquePupuk)
    {
        $this->groupedData = $groupedData;
        $this->uniquePupuk = $uniquePupuk;
    }

    public function collection()
    {
        return $this->groupedData->values(); // karena ini Collection of Collection
    }

public function map($items): array
{
    $row = [];

    $firstItem = $items->first();
    $row[] = "\t" . $firstItem->nik;

    for ($mt = 1; $mt <= 3; $mt++) {
        $rdkkMt = $items->first(); // Tetap pakai item pertama

        $komoditas = $rdkkMt ? $rdkkMt->komoditi : null;
        $luasLahan = $rdkkMt && $rdkkMt->pengajuan ? $rdkkMt->pengajuan->luasan : null;

        // Paksa isi jadi string agar tidak dianggap kosong
        $row[] = (string) ($komoditas !== null && $komoditas !== '' ? $komoditas : '0');
        $row[] = (string) ($luasLahan !== null && $luasLahan !== '' ? $luasLahan : '0');

        foreach ($this->uniquePupuk as $namaPupuk) {
            $rdkk = $items->firstWhere(function ($item) use ($namaPupuk) {
                return $item->pupuk && $item->pupuk->nama_pupuk === $namaPupuk;
            });

            $volume = $rdkk ? $rdkk->{"volume_pupuk_mt{$mt}"} : 0;

            // Paksa tampilkan 0 jika null atau kosong
            $row[] = (string) ($volume !== null && $volume !== '' ? $volume : 0);
        }
    }

    return $row;
}



    public function headings(): array
    {
        $headings = ['KTP'];

        for ($mt = 1; $mt <= 3; $mt++) {
            $headings[] = "Komoditas MT{$mt}";
            $headings[] = "Luas Lahan (Ha) MT{$mt}";
            foreach ($this->uniquePupuk as $namaPupuk) {
                $headings[] = "Pupuk {$namaPupuk} (Kg) MT{$mt}";
            }
        }

        return $headings;
    }

}


// class RdkkExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     protected $tahun;
//     protected $periode;
//     protected $komoditi;

//     public function __construct($tahun, $periode, $komoditi)
//     {
//         $this->tahun = $tahun;
//         $this->periode = $periode;
//         $this->komoditi = $komoditi;
//     }

//     public function collection()
//     {
//         $rdkk = Rdkk::with(['pupuk', 'pengajuan', 'petani']) 
//             ->when($this->tahun, function ($query) {
//                 $query->where('tahun', $this->tahun);
//             })
//             ->when($this->periode, function ($query) {
//                 $query->where('periode', $this->periode);
//             })
//             ->when($this->komoditi, function ($query) {
//                 $query->where('komoditi', $this->komoditi);
//             })
//             ->get()
//             ->groupBy('nik'); 

//             // dd($rdkk);
//         return $rdkk;
//     }

//     public function map($groupedRdkk): array
//     {
//         // Ambil data umum dari baris pertama
//         $first = $groupedRdkk->first();

//         // Siapkan data statis (nik, komoditi, dst)
//         $data = [
//             "\t" . $first->nik,
//             $first->komoditi,
//             $first->pengajuan->luasan,
//         ];

//         // Tambahkan data dinamis berdasarkan jumlah baris per nik
//         $pupukIds = [];
//         $mt1s = [];
//         $mt2s = [];
//         $mt3s = [];
//         $totals = [];

//         foreach ($groupedRdkk as $item) {
//             $pupukIds[] = $item->id_pupuk;
//             $mt1s[] = $item->volume_pupuk_mt1;
//             $mt2s[] = $item->volume_pupuk_mt2;
//             $mt3s[] = $item->volume_pupuk_mt3;
//             $totals[] = $item->total_harga;
//         }

//         // Gabungkan semua ke satu array
//         return array_merge($data, $pupukIds, $mt1s, $mt2s, $mt3s, $totals);
//     }



//     public function headings(): array
// {
//     $static = ['KTP', 'Komoditas', 'luasan'];

//     $max = 6; // ubah sesuai maksimum data per nik yang kamu izinkan

//     $pupuk = collect(range(1, $max))->map(fn($i) => "Pupuk_$i")->toArray();
//     $mt1 = collect(range(1, $max))->map(fn($i) => "MT1_$i")->toArray();
//     $mt2 = collect(range(1, $max))->map(fn($i) => "MT2_$i")->toArray();
//     $mt3 = collect(range(1, $max))->map(fn($i) => "MT3_$i")->toArray();
//     $total = collect(range(1, $max))->map(fn($i) => "Total_$i")->toArray();

//     return array_merge($static, $pupuk, $mt1, $mt2, $mt3, $total);
// }


//     public function columnFormats(): array
//     {
//     return [
//         'A' => NumberFormat::FORMAT_TEXT, // Kolom KTP (kolom A) sebagai teks
//         'C' => NumberFormat::FORMAT_TEXT, // Kolom id_pengajuan (kalau perlu)
//     ];
//     }
// }
