<?php

namespace App\Http\Controllers;

use App\Models\Penyaluran;
use App\Models\Pupuk;
use App\Models\Rdkk;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request){
        $stok = Stok::with(['pupuk'])->orderBy('tahun', 'desc')->orderBy('periode', 'desc')->get();
        $pupuk = Pupuk::all();
        $rdkk = Rdkk::where('status_penyaluran',1)->get();
        return view('poktan.stok',compact('stok', 'pupuk','rdkk'));
    }

    public function store(Request $request){
        $request->validate([
            'pupuk' => 'required',
            'tahun' => 'required',
            'periode' => 'required',
            'pupuk_datang' => 'required|numeric',
        ]);
        $existingStok = Stok::where('id_pupuk', $request->pupuk)
            ->where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->first();
        if ($existingStok) {
            return redirect()->back()->with('error', 'Stok untuk pupuk ini pada tahun dan periode yang sama sudah ada.');
        }

        Stok::create([
            'id_pupuk' => $request->pupuk,
            'tahun' => $request->tahun,
            'periode' => $request->periode,
            'pupuk_datang' => $request->pupuk_datang,
            'pupuk_tersisa' => $request->pupuk_datang, // Inisialisasi stok tersisa dengan jumlah datang
        ]);

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan');
    }

    public function sync(Request $request){
        $request->validate([
            'id'=> 'required|exists:stok,id',
            'pupuk' => 'required',
            'tahun' => 'required',
            'periode' => 'required',
            'pupuk_datang' => 'required|numeric',
        ]);

        $komoditi = Penyaluran::where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->where('status_pembayaran', 1)
            ->pluck('komoditi')
            ->unique()
            ->values()
            ->toArray(); 
        // dd($komoditi);
        $totalPupuk = Rdkk::join('penyaluran as p', function($join) {
                $join->on('rdkk.nik', '=', 'p.nik')
                    ->on('rdkk.komoditi', '=', 'p.komoditi');
            })
            ->where('rdkk.tahun', '2025')
            ->where('rdkk.periode', '1')
            ->where('rdkk.id_pupuk', $request->id)
            ->where('rdkk.status_penyaluran', 1)
            ->where('p.tahun', '2025')
            ->where('p.periode', '1')
            ->where('p.status_pembayaran', '1')
            ->whereIn('rdkk.komoditi', $komoditi)
            ->select(DB::raw('SUM(rdkk.volume_pupuk_mt1 + rdkk.volume_pupuk_mt2 + rdkk.volume_pupuk_mt3) as total_pupuk'))
            ->value('total_pupuk');
                // Tampilkan hasil total pupuk
                // dd($totalPupuk);
        
        $stok = Stok::findOrFail($request->id);
        $stoktersisa = $stok->pupuk_datang-$totalPupuk;
        if ($stok) {
            // Update existing stok
            if ($stok->pupuk_tersisa != $stoktersisa){
                if ($stoktersisa < 0) {
                    $stoktersisa = 0; // Pastikan stok tersisa tidak negatif
                    $stok->save();
                    return redirect()->back()->with('success', 'Stok berhasil disinkronkan');
                }
                else{
                $stok->pupuk_tersisa = $stoktersisa; // Update stok tersisa sesuai dengan stok datang
                $stok->save();
                return redirect()->back()->with('success', 'Stok berhasil disinkronkan');
                }
            }else {
                return redirect()->back()->with('success', 'Stok sudah sesuai, sinkronisasi berhasil');
            }
        } 

        return redirect()->back()->with('success', 'Stok berhasil disinkronkan');
    }
     public function destroy(Request $request){
        $request->validate([
            'id' => 'required|exists:stok,id',
        ]);

        $stok = Stok::findOrFail($request->id);

        $komodititersalur= Penyaluran::where('tahun', $stok->tahun)
            ->where('periode', $stok->periode)
            ->where('status_pembayaran', 1)
            ->pluck('komoditi')
            ->unique()
            ->values()
            ->toArray();

            foreach ($komodititersalur as $komoditi) {
                $pupuk = Rdkk::where('tahun', $stok->tahun)
                    ->where('periode', $stok->periode)
                    ->where('komoditi', $komoditi)
                    ->where('id_pupuk', $stok->id_pupuk)
                    ->where('status_penyaluran', 1)
                    ->first();
                if ($pupuk) {
                    return redirect()->back()->with('error', 'Stok tidak dapat dihapus karena sudah digunakan dalam penyaluran');
                }
            }
        
        if ($stok) {
            $stok->delete();
            return redirect()->back()->with('success', 'Stok berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Stok tidak ditemukan');
        }
     }


}
