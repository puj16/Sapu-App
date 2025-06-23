<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Petani;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    //
    public function index(Request $request)
    {
        $nik = Auth::guard('petani')->user()->nik;
        $tahunajuan = Pengajuan::where('nik', $nik)->get();
    
        // Ambil tahun dari request, jika tidak ada ambil tahun terbaru
        $tahun = $request->input('tahun', Pengajuan::where('nik', $nik)->max('tahun'));
    
        // Query untuk mendapatkan data dikelompokkan berdasarkan komoditi
        $pengajuan = Pengajuan::where('nik', $nik)
                            ->where('tahun', $tahun)
                            ->orderBy('komoditi')
                            ->get()
                            ->groupBy('komoditi'); // Kelompokkan data berdasarkan komoditi
    
        // Query lahan berdasarkan NIK
        $lahan = Lahan::where('add_by', $nik)->get();
    
        return view('petani.pengajuan', compact('pengajuan', 'lahan', 'tahun','tahunajuan'));
    }

    public function show(Request $request)
    {
        $tahunajuan = Pengajuan::select('tahun')->distinct()->get();
        $tahun = $request->input('tahun', Pengajuan::max('tahun'));
        $status_validasi = $request->input('status_validasi', 0);        // Query untuk mendapatkan data berdasarkan tahun dan status_validasi, dikelompokkan berdasarkan komoditi
        if ($status_validasi=='all') {
            $pengajuan = Pengajuan::where('tahun', $tahun)
            ->orderBy('komoditi')
            ->get()
            ->groupBy('komoditi'); 
        }else {
                $pengajuan = Pengajuan::where('tahun', $tahun)
                            ->where('status_validasi', $status_validasi)
                            ->orderBy('komoditi')
                            ->get()
                            ->groupBy('komoditi'); // Kelompokkan data berdasarkan komoditi
        }
        return view('poktan.pengajuan', compact('pengajuan', 'tahun', 'tahunajuan', 'status_validasi'));
    }

    
    

    // public function store(Request $request){
    //     // dd($request)->all();
    //     $request->merge([
    //         'luasan' => str_replace(',', '.', $request->luasan)
    //     ]);

    //     $request->validate([
    //         'nop' => 'required|string|max:50',
    //         'nik' => 'required|string|max:16',
    //         'luasan' => 'required|numeric|min:0.001|max:2.000',
    //         'tahun' => 'required|string',
    //         'komoditi' => 'required|string' // Opsional, hanya jika diunggah
    //     ]);

    //     $petani = Petani::where('nik', $request->nik)->first();
    //     if (!$petani || $petani->kk=='Belum terisi' && $petani->ktp=='Belum terisi') {
    //         return redirect()->route('pengajuan.index')->with('error', 'Berkas KTP / KK belum lengkap.');
    //     }
    //     $lahan = Lahan::where('NOP', $request->nop)->first();
    //     if (!$lahan|| empty($lahan->Foto_SPPT) || $lahan->Foto_SPPT=='Belum terisi') {
    //         return redirect()->route('pengajuan.index')->with('error', 'Berkas SPPT belum ada.');
    //     }

    //     Pengajuan::create([
    //         'NOP'=>$request->nop,
    //         'nik'=>$request->nik,
    //         'luasan'=>$request->luasan,
    //         'tahun'=>$request->tahun,
    //         'komoditi'=>$request->komoditi,
    //     ]);

    //     return redirect()->route('pengajuan.index')->with('success', 'Data pengajuan berhasil ditambahkan.');
    // }


    public function store(Request $request)
{
    $request->merge([
        'luasan' => array_map(function ($value) {
            return str_replace(',', '.', $value);
        }, $request->luasan)
    ]);

    $request->validate([
        'nik' => 'required|string|max:16',
        'tahun' => 'required|string',
        'nop' => 'required|array',
        'luasan' => 'required|array',
        'komoditi' => 'required|array',
        'nop.*' => 'required|string|max:50',
        'luasan.*' => 'required|numeric|min:0.001|max:2.000',
        'komoditi.*' => 'required|string',
    ]);

    if (count($request->komoditi) !== count(array_unique($request->komoditi))) {
        return redirect()->route('pengajuan.index')->with('error', 'Komoditi tidak boleh ada yang sama.');
    }
    // dd($request->all());


    $petani = Petani::where('nik', $request->nik)->first();
    if (!$petani || $petani->kk == 'Belum terisi' && $petani->ktp == 'Belum terisi') {
        return redirect()->route('pengajuan.index')->with('error', 'Berkas KTP / KK belum lengkap.');
    }

    foreach ($request->nop as $index => $nop) {
         if (empty($request->komoditi[$index])) {
        continue;
        }

        $lahan = Lahan::where('NOP', $nop)->first();
        if (!$lahan || empty($lahan->Foto_SPPT) || $lahan->Foto_SPPT == 'Belum terisi') {
            return redirect()->route('pengajuan.index')->with('error', 'Berkas SPPT untuk NOP ' . $nop . ' belum ada.');
        }

        $existing = Pengajuan::where('nik', $request->nik)
            ->where('tahun', $request->tahun)
            ->where('NOP', $nop)
            ->first();

        if ($existing) {
            return redirect()->route('pengajuan.index')->with('error', "Ada telah melakukan pengajuan untuk tahun ini.");
        }

        Pengajuan::create([
            'NOP' => $nop,
            'nik' => $request->nik,
            'luasan' => $request->luasan[$index],
            'tahun' => $request->tahun,
            'komoditi' => $request->komoditi[$index],
        ]);
    }

    return redirect()->route('pengajuan.index')->with('success', 'Semua data pengajuan berhasil ditambahkan.');
}


    public function update(Request $request, $id_pengajuan)
    {
        $request->merge([
            'luasan' => str_replace(',', '.', $request->luasan)
        ]);
        $request->validate([
            'id_pengajuan'=>'required',
            'nop' => 'required|string|max:50',
            'nik' => 'required|string|max:16',
            'luasan' => 'required|numeric|min:0.001',
            'tahun' => 'required|string',
            'komoditi' => 'required|string' // Opsional, hanya jika diunggah
        ]);

        $pengajuan = Pengajuan::findOrFail($id_pengajuan);
        
        $pengajuan->NOP=$request->nop;
        $pengajuan->nik=$request->nik;
        $pengajuan->luasan=$request->luasan;
        $pengajuan->tahun=$request->tahun;
        $pengajuan->komoditi=$request->komoditi;
        $pengajuan->status_validasi=0;

        $pengajuan->update();

        return redirect()->route('pengajuan.index')->with('success', 'Data pengajuan berhasil diperbarui.');
    }


    public function validasi(Request $request, $id_pengajuan)
    {
        $request->validate([
            'catatan' => 'nullable|string', // Catatan tidak wajib diisi
            'status_validasi' => 'required|integer' // Hanya divalidasi jika dikirim
        ]);

        $pengajuan = Pengajuan::findOrFail($id_pengajuan);

        if ($request->filled('catatan')) { // Gunakan filled() agar mengecek nilai tidak kosong
            $pengajuan->catatan = $request->catatan;
            $pengajuan->status_validasi = 2;
        } else {
            $pengajuan->status_validasi = 1;
        }

        $pengajuan->save(); // Simpan perubahan

        return redirect()->route('pengajuan.show')->with('success', 'Data pengajuan berhasil diperbarui.');
    }


    public function destroy(Request $request, $id_pengajuan)
        {
            $pengajuan = Pengajuan::findOrFail($id_pengajuan);
            $pengajuan->delete();
            return redirect()->route('pengajuan.index')->with('success', 'Data pengajuan berhasil dihapus.');
        }

    public function detailBerkas(Request $request, $id_pengajuan)
    {
        $pengajuan = Pengajuan::findOrFail($id_pengajuan);
        if (!$pengajuan) {
            return redirect()->route('pengajuan.index')->with('error', 'Data pengajuan tidak ditemukan.');
        }
        $nop = $pengajuan->NOP;
        $lahan = Lahan::where('NOP', $nop)->first();

        $nik = $pengajuan->nik;
        $petani =Petani::where('nik', $nik)->first();;

        return view('petani.berkasajuan',compact('pengajuan','lahan','petani'));
    }

    public function cekBerkas(Request $request, $id_pengajuan)
    {
        $pengajuan = Pengajuan::findOrFail($id_pengajuan);
        if (!$pengajuan) {
            return redirect()->route('pengajuan.index')->with('error', 'Data pengajuan tidak ditemukan.');
        }
        $nop = $pengajuan->NOP;
        $lahan = Lahan::where('NOP', $nop)->first();

        $nik = $pengajuan->nik;
        $petani =Petani::where('nik', $nik)->first();;

        // dd($petani);
        return view('poktan.berkas',compact('pengajuan','lahan','petani'));
    }
    
    public function report (Request $request)
    {
        $request->validate([
            'tahun' => 'required',
            'status_validasi' => 'required' // Opsional, hanya jika diunggah
        ]);

        
        // Ambil tahun dari request, jika tidak ada, gunakan tahun terbaru
        $tahun = $request->input('tahun', Pengajuan::max('tahun'));

        // Ambil status_validasi dari request, defaultnya 0 (Menunggu Validasi)
        $status_validasi = $request->input('status_validasi', 'all');

        $petani = Petani::all();
        $lahan = Lahan::all();
        // Query untuk mendapatkan data berdasarkan tahun dan status_validasi, dikelompokkan berdasarkan komoditi
        if ($status_validasi=='all') {
            $pengajuan = Pengajuan::where('tahun', $tahun)
            ->orderBy('komoditi')
            ->get()
            ->groupBy('komoditi'); // Kelompokkan data berdasarkan komoditi
        }else {
            # code...
                $pengajuan = Pengajuan::where('tahun', $tahun)
                            ->where('status_validasi', $status_validasi)
                            ->orderBy('komoditi')
                            ->get()
                            ->groupBy('komoditi'); // Kelompokkan data berdasarkan komoditi
        }

        $pdf = Pdf::loadView('pdf.Pengajuanreport',compact('pengajuan', 'tahun', 'status_validasi','petani','lahan'));
        return $pdf->stream();
    
    

    }

}
