<?php

namespace App\Http\Controllers;

use App\Exports\RdkkExport;
use App\Models\Lahan;
use App\Models\Pengajuan;
use App\Models\Penyaluran;
use App\Models\Petani;
use App\Models\Pupuk;
use App\Models\Rdkk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RdkkController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data unik untuk dropdown filter
        $tahunrdkk = Rdkk::select('tahun')->distinct()->get();
        $komoditirdkk = Rdkk::select('komoditi')->distinct()->get();
        $perioderdkk = Rdkk::select('periode')->distinct()->get();

        $petani= Petani::all(); // Ambil hanya kolom 'nik' sebagai array
        $pengajuan = Pengajuan::where('status_validasi', 1)
        ->where('tahun', now()->subYear()->year) // Tambahkan kondisi tahun lalu
        ->get();        
        $pupuk = Pupuk::all();
        // Ambil nilai filter dari request
        $tahun = $request->input('tahun', Rdkk::latest('created_at')->value('tahun'));
        $periode = $request->input('periode', Rdkk::latest('created_at')->value('periode'));
        $komoditi = $request->input('komoditi', Rdkk::latest('created_at')->value('komoditi'));
        

        // Query data dengan filter dan kelompokkan berdasarkan NIK
        $rdkk = Rdkk::with(['pupuk', 'pengajuan', 'petani']) // Load relasi pupuk
        ->when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })
        ->when($periode, function ($query, $periode) {
            return $query->where('periode', $periode);
        })
        ->when($komoditi, function ($query, $komoditi) {
            return $query->where('komoditi', $komoditi);
        })
        ->orderBy('tahun', 'desc')
        ->get()
        ->groupBy('nik');

        
        $uniqueNamaPupuk = $rdkk->flatMap(function ($items) {
            return $items->pluck('pupuk.nama_pupuk');
        })->unique();
        
        
            // dd($pengajuan);
        return view('poktan.rdkk', compact('rdkk', 'tahunrdkk', 'komoditirdkk', 'perioderdkk', 'tahun', 'periode', 'komoditi','uniqueNamaPupuk','petani','pengajuan','pupuk'));
    }

    public function add(Request $request){
        $tahunrdkk = Rdkk::select('tahun')->distinct()->get();
        $komoditirdkk = Rdkk::select('komoditi')->distinct()->get();
        $perioderdkk = Rdkk::select('periode')->distinct()->get();

        $petani= Petani::all(); // Ambil hanya kolom 'nik' sebagai array
        $pengajuan = Pengajuan::where('status_validasi', 1)
        ->where('tahun', now()->subYear()->year) // Tambahkan kondisi tahun lalu
        ->get();        
        $pupuk = Pupuk::all();
        // Ambil nilai filter dari request
        $tahun = $request->input('tahun', Rdkk::latest('created_at')->value('tahun'));
        $periode = $request->input('periode', Rdkk::latest('created_at')->value('periode'));
        $komoditi = $request->input('komoditi', Rdkk::latest('created_at')->value('komoditi'));
        

        // Query data dengan filter dan kelompokkan berdasarkan NIK
        $rdkk = Rdkk::with(['pupuk', 'pengajuan', 'petani']) // Load relasi pupuk
        ->when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })
        ->when($periode, function ($query, $periode) {
            return $query->where('periode', $periode);
        })
        ->when($komoditi, function ($query, $komoditi) {
            return $query->where('komoditi', $komoditi);
        })
        ->orderBy('tahun', 'desc')
        ->get()
        ->groupBy('nik');

        
        $uniqueNamaPupuk = $rdkk->flatMap(function ($items) {
            return $items->pluck('pupuk.nama_pupuk');
        })->unique();
        
        
            // dd($pengajuan);
        return view('poktan.pengajuanRdkk', compact('rdkk', 'tahunrdkk', 'komoditirdkk', 'perioderdkk', 'tahun', 'periode', 'komoditi','uniqueNamaPupuk','petani','pengajuan','pupuk'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'tahun' => 'required|numeric',
    //         'periode' => 'required|numeric',
    //         'komoditi' => 'required|string',
    //         'pupuk' => 'required|array',
    //         'pupuk.*' => 'required|exists:pupuk,id',
    //         'vol' => 'required|array',
    //         'vol.*' => 'required|numeric|min:0',
    //     ]);

    //     $tahun = $request->input('tahun');
    //     $periode = $request->input('periode');
    //     $komoditi = $request->input('komoditi');
    //     $pupuk_ids = $request->input('pupuk');      // array of selected pupuk IDs
    //     $vol_per_ha = $request->input('vol');       // array of vol/ha
    //     $mt_checks = $request->input('mt_check', []); // array like ['mt1', 'mt3']

    //     // Ambil pengajuan tahun sebelumnya
    //     $pengajuanList = Pengajuan::where('komoditi', $komoditi)
    //         ->where('status_validasi', 1)
    //         ->where('tahun', $tahun - 1)
    //         ->get();

    //     foreach ($pengajuanList as $pengajuan) {
    //         $luasan = $pengajuan->luasan;

    //         foreach ($pupuk_ids as $index => $idPupuk) {
    //             $harga = DB::table('pupuk')->where('id', $idPupuk)->value('harga');
    //             $vol_ha = $vol_per_ha[$index];

    //             // Hitung volume berdasarkan checkbox musim tanam
    //             $volume_mt1 = in_array('mt1', $mt_checks) ? $luasan * $vol_ha : 0;
    //             $volume_mt2 = in_array('mt2', $mt_checks) ? $luasan * $vol_ha : 0;
    //             $volume_mt3 = in_array('mt3', $mt_checks) ? $luasan * $vol_ha : 0;

    //             $totalVolume = $volume_mt1 + $volume_mt2 + $volume_mt3;
    //             $totalHarga = $totalVolume * $harga;

    //             Rdkk::create([
    //                 'tahun' => $tahun,
    //                 'periode' => $periode,
    //                 'komoditi' => $komoditi,
    //                 'nik' => $pengajuan->nik,
    //                 'id_pengajuan' => $pengajuan->id_pengajuan,
    //                 'id_pupuk' => $idPupuk,
    //                 'volume_pupuk_mt1' => $volume_mt1,
    //                 'volume_pupuk_mt2' => $volume_mt2,
    //                 'volume_pupuk_mt3' => $volume_mt3,
    //                 'total_harga' => $totalHarga,
    //                 'status_penyaluran' => 0,
    //                 'info' => null,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }
    //     }

    //     return redirect()->route('rdkk.index')->with('success', 'RDKK berhasil disimpan.');
    // }

    public function store(Request $request)
        {
        $request->validate([
            'tahun' => 'required|numeric',
            'periode' => 'required|numeric',
            'komoditi' => 'required|in:Padi,Jagung,Tebu',
        ]);

        $tahun = $request->input('tahun');
        $periode = $request->input('periode');
        $komoditi = $request->input('komoditi');
        $pupuk_ids = 0;      // array of selected pupuk IDs
        $vol_per_ha = 0; // array of vol/ha

        if ($komoditi == 'Padi') {
            $pupuk_ids = [1, 2];
            $vol_per_ha = 1000;
        } elseif ($komoditi == 'Jagung') {
            $pupuk_ids = [1, 2]; // Ganti sesuai id pupuk untuk jagung
            $vol_per_ha = 1000;   // Contoh volume per ha untuk jagung, sesuaikan jika perlu
        }elseif($komoditi=='Tebu'){
            $pupuk_ids = [1]; // Ganti sesuai id pupuk untuk tebu
            $vol_per_ha = 1300;   // Contoh volume per ha untuk tebu, sesuaikan jika perlu
        } else {
            return redirect()->back()->with('error', 'Komoditi tidak valid.');       
            # code...
        }
        // Ambil pengajuan tahun sebelumnya
        $pengajuanList = Pengajuan::where('komoditi', $komoditi)
            ->where('status_validasi', 1)
            ->where('tahun', $tahun - 1)
            ->get();

        foreach ($pengajuanList as $pengajuan) {
            $luasan = $pengajuan->luasan;

                $existingRdkk = Rdkk::where('tahun', $tahun)
                    ->where('periode', $periode)
                    ->where('komoditi', $komoditi)
                    ->where('nik', $pengajuan->nik)
                    ->where('id_pengajuan', $pengajuan->id_pengajuan)
                    ->first();

                if ($existingRdkk) {
                    return redirect()->back()->with('error', 'Data RDKK untuk komoditi ' .$komoditi. ' tahun ' . $tahun . ' periode ' . $periode . ' sudah ada.');
                }


            foreach ($pupuk_ids as $idPupuk) {
                $harga = DB::table('pupuk')->where('id', $idPupuk)->value('harga');
                $vol_ha = $vol_per_ha;

                // Kondisi volume per MT sesuai komoditi
                if ($komoditi == 'Padi') {
                    $volume_mt1 = $luasan * $vol_ha;
                    $volume_mt2 = 0;
                    $volume_mt3 = 0;
                } elseif ($komoditi == 'Jagung') {
                    $volume_mt1 = 0;
                    $volume_mt2 = $luasan * $vol_ha;
                    $volume_mt3 = $luasan * $vol_ha;
                } elseif($komoditi=='Tebu') {
                    $volume_mt1 = 0;
                    $volume_mt2 = $luasan * $vol_ha;
                    $volume_mt3 = 0;
                }else { 
                    return redirect()->back()->with('error', 'Komoditi tidak valid.');       
                }

                $totalVolume = $volume_mt1 + $volume_mt2 + $volume_mt3;
                $totalHarga = $totalVolume * $harga;

                Rdkk::create([
                    'tahun' => $tahun,
                    'periode' => $periode,
                    'komoditi' => $komoditi,
                    'nik' => $pengajuan->nik,
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_pupuk' => $idPupuk,
                    'volume_pupuk_mt1' => $volume_mt1,
                    'pengajuan_mt1' => $volume_mt1,
                    'volume_pupuk_mt2' => $volume_mt2,
                    'pengajuan_mt2' => $volume_mt2,
                    'volume_pupuk_mt3' => $volume_mt3,
                    'pengajuan_mt3' => $volume_mt3,
                    'total_harga' => $totalHarga,
                    'status_penyaluran' => 0,
                    'info' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);



            }
        }

        return redirect()->route('rdkk.add')->with('success', 'RDKK berhasil disimpan.');
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'tahun' => 'required|integer',
    //         'periode' => 'required|integer',
    //         'komoditi' => 'required|string',
    //         'nik' => 'required|string',
    //         'id_pengajuan' => 'required|integer',
    //         'pupuk' => 'required|array', 
    //         'pupuk.*' => 'integer',
    //         'mt1' => 'required|array',
    //         'mt1.*' => 'integer',
    //         'mt2' => 'required|array',
    //         'mt2.*' => 'integer',
    //         'mt3' => 'required|array',
    //         'mt3.*' => 'integer',
    //     ]);


    //     $simpanpermanen = Rdkk::where('komoditi', $validated['komoditi'])
    //         ->where('tahun', $validated['tahun'])
    //         ->where('periode', $validated['periode'])
    //         ->first();
        
    //     if ($simpanpermanen && $simpanpermanen->status_penyaluran == 1) {
    //         return redirect()->back()->with('error', 'Data sudah disimpan secara permanen');
    //     }
    //     else{
    //     foreach ($request->pupuk as $key => $pupukId) {
    //         // Ambil harga pupuk dari tabel pupuk
    //         $cekRdkk = Rdkk::where('komoditi', $validated['komoditi'])
    //             ->where('tahun', $validated['tahun'])
    //             ->where('periode', $validated['periode'])
    //             ->where('nik', $validated['nik'])
    //             ->where('id_pupuk', $pupukId)
    //             ->exists();

    //         if ($cekRdkk) {
    //             return redirect()->back()->with('error', 'Data sudah ada');
    //         }
    //         else{
    //             $hargaPupuk = DB::table('pupuk')->where('id', $pupukId)->value('harga');

    //             // Hitung total volume pupuk
    //             $totalVolume = $request->mt1[$key] + $request->mt2[$key] + $request->mt3[$key];

    //             // Hitung total harga
    //             $totalHarga = $totalVolume * $hargaPupuk;

    //             // $data[] = [
    //             //     'pupukId' => $pupukId,
    //             //     'hargaPupuk' => $hargaPupuk,
    //             //     'totalVolume' => $totalVolume,
    //             //     'totalHarga' => $totalHarga
    //             // ]; 
    //             // Insert data ke tabel rdkk
    //             Rdkk::create([
    //                 'tahun' => $validated['tahun'],
    //                 'periode' => $validated['periode'],
    //                 'komoditi' => $validated['komoditi'],
    //                 'nik' => $validated['nik'],
    //                 'id_pengajuan' => $validated['id_pengajuan'],
    //                 'id_pupuk' => $pupukId,
    //                 'volume_pupuk_mt1' => $request->mt1[$key],
    //                 'volume_pupuk_mt2' => $request->mt2[$key],
    //                 'volume_pupuk_mt3' => $request->mt3[$key],
    //                 'total_harga' => $totalHarga, // Simpan total harga
    //                 'status_penyaluran' => 0,
    //                 'info' => null,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }
    //     }}
    //     // dd($data);
    //     return redirect()->back()->with('success', 'Data RDKK berhasil ditambahkan!');
    // }

    public function updatepernik(Request $request, $nik)
    {
        // dd($request)->all();

        $request->validate([
            'id_pengajuan' => 'required|exists:pengajuan,id_pengajuan',
            'id_rdkk' => 'required|array',
            'id_rdkk.*' => 'exists:rdkk,id_RDKK',
            'pupuk' => 'required|array',
            'pupuk.*' => 'exists:pupuk,id',
            'mt1' => 'required|array',
            'mt1.*' => 'nullable|numeric|min:0',
            'mt2' => 'required|array',
            'mt2.*' => 'nullable|numeric|min:0',
            'mt3' => 'required|array',
            'mt3.*' => 'nullable|numeric|min:0',
        ]);
    
        // 🛑 Cek apakah data dengan NIK tersebut ada
        $rdkkData = Rdkk::where('nik', $nik)->get();
        if ($rdkkData->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    
        // 🔹 Inisialisasi total harga
        $total_bayar=0;
        $totalHarga = 0;
    
        foreach ($request->id_rdkk as $index => $id_rdkk) {
            $pupukId = $request->pupuk[$index];
            $mt1 = (int) $request->mt1[$index];
            $mt2 = (int) $request->mt2[$index];
            $mt3 = (int) $request->mt3[$index];
    
            // 🔹 Ambil harga pupuk dari database
            $hargaPupuk = Pupuk::where('id', $pupukId)->value('harga');
    
            // 🔹 Hitung total harga berdasarkan volume pupuk
            $totalVolume = $mt1 + $mt2 + $mt3;


            $totalHarga = $totalVolume * $hargaPupuk;
            $total_bayar+=$totalHarga;
            // 🔹 Update data di tabel RDKK berdasarkan id_rdkk
            Rdkk::where('id_RDKK', $id_rdkk)->update([
                'id_pengajuan' => $request->id_pengajuan,
                'id_pupuk' => $pupukId,
                'volume_pupuk_mt1' => $mt1,
                'volume_pupuk_mt2' => $mt2,
                'volume_pupuk_mt3' => $mt3,
                'total_harga' => $totalHarga,
            ]);
            $dataRdkk = Rdkk::where('id_RDKK', $id_rdkk)->select('komoditi','periode','tahun')->first();
        }
        Penyaluran::where('nik',$nik)
            ->where('tahun', $dataRdkk->tahun)
            ->where('periode', $dataRdkk->periode)
            ->where('komoditi', $dataRdkk->komoditi)->update([
                'total_bayar' => $total_bayar
            ]);

        // 🔹 Simpan total harga ke salah satu record (misalnya, record pertama)    
        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

public function update(Request $request){
    $request->validate([
        'tahun' => 'required|numeric',
        'periode' => 'required|numeric',
        'komoditi' => 'required|string',
        'pupuk' => 'required|array',
        'pupuk.*' => 'required|exists:pupuk,id',
        'vol' => 'required|array',
        'vol.*' => 'required|numeric|min:0',
    ]);

    $tahun = $request->input('tahun');
    $periode = $request->input('periode');
    $komoditi = $request->input('komoditi');
    $pupuk_ids = $request->input('pupuk');
    $vol_per_ha = $request->input('vol');

    // Tentukan musim tanam berdasarkan komoditi
    if ($komoditi == 'Padi') {
        $mt_checks = ['mt1'];
    } elseif ($komoditi == 'Jagung') {
        $mt_checks = ['mt2', 'mt3'];
    } elseif ($komoditi == 'Tebu') {
        $mt_checks = ['mt2'];
    } else {
        return redirect()->back()->with('error', 'Komoditi tidak valid.');
    }

    foreach ($pupuk_ids as $index => $idPupuk) {
        $vol_ha = $vol_per_ha[$index];
        $harga = DB::table('pupuk')->where('id', $idPupuk)->value('harga');

        // Ambil semua RDKK yang cocok dengan pupuk ini
        $rdkks = RDKK::where('tahun', $tahun)
            ->where('periode', $periode)
            ->where('komoditi', $komoditi)
            ->where('id_pupuk', $idPupuk)
            ->where('status_penyaluran', 0)
            ->get();

        foreach ($rdkks as $rdkk) {
            $luasan = Pengajuan::where('id_pengajuan', $rdkk->id_pengajuan)->value('luasan');

            $volume_mt1 = in_array('mt1', $mt_checks) ? $luasan * $vol_ha : 0;
            $volume_mt2 = in_array('mt2', $mt_checks) ? $luasan * $vol_ha : 0;
            $volume_mt3 = in_array('mt3', $mt_checks) ? $luasan * $vol_ha : 0;

            $totalVolume = $volume_mt1 + $volume_mt2 + $volume_mt3;
            $totalHarga = $totalVolume * $harga;

            $rdkk->update([
                'volume_pupuk_mt1' => $volume_mt1,
                'volume_pupuk_mt2' => $volume_mt2,
                'volume_pupuk_mt3' => $volume_mt3,
                'total_harga' => $totalHarga,
            ]);
        }
    }

    return redirect()->route('rdkk.index')->with('success', 'Volume pupuk berhasil diperbarui.');
}
    

    public function destroy(Request $request) 
    {
        $validated = $request->validate([
            'id_rdkk' => 'required|array'
        ]);
    
        // Hapus semua id_rdkk dalam satu query
        Rdkk::whereIn('id_RDKK', $validated['id_rdkk'])->delete();
    
        return back()->with('success', 'Data berhasil dihapus');
    }
    
 public function report(Request $request)
 {
    $request->validate([
        'tahun'=>'required',
        'periode'=>'required',
        'komoditi'=>'required',
        'jenis'=>'required',
    ]);

    $tahun = $request->input('tahun', Rdkk::latest('created_at')->value('tahun'));
    $periode = $request->input('periode', Rdkk::latest('created_at')->value('periode'));
    $komoditi = $request->input('komoditi', Rdkk::latest('created_at')->value('komoditi'));
    

    // Query data dengan filter dan kelompokkan berdasarkan NIK
    $rdkk = Rdkk::with(['pupuk', 'pengajuan', 'petani']) // Load relasi pupuk
    ->when($tahun, function ($query, $tahun) {
        return $query->where('tahun', $tahun);
    })
    ->when($periode, function ($query, $periode) {
        return $query->where('periode', $periode);
    })
    ->when($komoditi, function ($query, $komoditi) {
        return $query->where('komoditi', $komoditi);
    })
    ->orderBy('tahun', 'desc')
    ->get()
    ->groupBy('nik');

    $dataRdkk = Rdkk::with(['pupuk', 'pengajuan', 'petani']) // Load relasi pupuk
    ->when($tahun, function ($query, $tahun) {
        return $query->where('tahun', $tahun);
    })
    ->when($periode, function ($query, $periode) {
        return $query->where('periode', $periode);
    })
    ->when($komoditi, function ($query, $komoditi) {
        return $query->where('komoditi', $komoditi);
    })
    ->orderBy('tahun', 'desc')
    ->get();

    $idPengajuan = $dataRdkk->pluck('id_pengajuan')->unique();
    $luasan =0;

    foreach ($idPengajuan as $id) {
        
            $pengajuan = Pengajuan::where('id_pengajuan',$id)->get()->first();
            // dd($pengajuan);
            // Ambil nilai Luas dari data Lahan
            $luas = $pengajuan->luasan;

            // Debugging untuk memastikan nilai Luas
            $luasan += $luas;
                # code...
    }

    $uniqueNamaPupuk = $rdkk->flatMap(function ($items) {
        return $items->pluck('pupuk.nama_pupuk');
    })->unique();
    
    
        // dd($pengajuan);

        if ($request->jenis == 'pengajuan') {
            $pdf = Pdf::loadView('pdf.pengajuanRdkkreport',compact('rdkk', 'tahun', 'periode', 'komoditi','uniqueNamaPupuk','dataRdkk','luasan'));
        } else {
            $pdf = Pdf::loadView('pdf.Rdkkreport',compact('rdkk', 'tahun', 'periode', 'komoditi','uniqueNamaPupuk','dataRdkk','luasan'));
        }
    return $pdf->stream();

 }


 public function export(Request $request){
         $tahun = $request->tahun;
        $periode = $request->periode;
        $komoditi = $request->komoditi;


    $data = Rdkk::with(['pupuk', 'pengajuan', 'petani']) // Load relasi pupuk
        ->when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })
        ->when($periode, function ($query, $periode) {
            return $query->where('periode', $periode);
        })
        ->when($komoditi, function ($query, $komoditi) {
            return $query->where('komoditi', $komoditi);
        })
        ->orderBy('tahun', 'desc')
        ->get();

    // Group by nik
    $grouped = $data->groupBy('nik');

    // Ambil semua nama pupuk unik
    $uniquePupuk = $data->pluck('pupuk.nama_pupuk')->unique()->values();

    return Excel::download(
        new RdkkExport($grouped, $uniquePupuk),
        "RDKK-{$komoditi} (" . date('Y-m-d H-i-s') . ").xlsx"
    );
    }
} 






