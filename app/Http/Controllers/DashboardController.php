<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Pengajuan;
use App\Models\Penyaluran;
use App\Models\Rdkk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function landing(Request $request) {
        $tahunrdkk = Rdkk::select('tahun')->distinct()->get();
        $komoditirdkk = Rdkk::select('komoditi')->distinct()->get();
        $perioderdkk = Rdkk::select('periode')->distinct()->get();

        // Ambil nilai filter dari request
        $tahun = $request->input('tahun', Rdkk::where('status_penyaluran', 1)->latest('created_at')->value('tahun'));
        $periode = $request->input('periode', Rdkk::where('status_penyaluran', 1)->latest('created_at')->value('periode'));
        $komoditi = $request->input('komoditi', Rdkk::where('status_penyaluran', 1)->latest('created_at')->value('komoditi'));
        

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
        })->where('status_penyaluran', 1)
        ->orderBy('tahun', 'desc')
        ->get()
        ->groupBy('nik')
        ->take(5);

        
        $uniqueNamaPupuk = $rdkk->flatMap(function ($items) {
            return $items->pluck('pupuk.nama_pupuk');
        })->unique();
        
        
            // dd($data);


        return view('petani.landing', compact('rdkk', 'tahunrdkk', 'komoditirdkk', 'perioderdkk', 'tahun', 'periode', 'komoditi','uniqueNamaPupuk'));
}

    public function dashboard(Request $request) {
        $user = Auth::user();
        $total = Pengajuan::count();
        $menunggu = Pengajuan::where('status_validasi', 0)->count();
        $berhasil = Pengajuan::where('status_validasi', 1)->count();
        $gagal = Pengajuan::where('status_validasi', 2)->count();
        $periodePenyaluran = Penyaluran::select('periode')->distinct()->get();
        $periode = $request->input('periode', Penyaluran::latest('created_at')->value('periode'));

        $data = [
            'menunggu' => $total > 0 ? ($menunggu / $total) * 100 : 0,
            'berhasil' => $total > 0 ? ($berhasil / $total) * 100 : 0,
            'gagal' => $total > 0 ? ($gagal / $total) * 100 : 0,
        ];

        $uniqBulan = range(1, 12);
        $uniqKomoditi = Penyaluran::select('komoditi')->where('tahun', Carbon::now()->year)->distinct()->pluck('komoditi');
        $series = [];
        $label = $uniqKomoditi->toArray();
        $progress = [];
        $donutData=[];
        $treemapData = [];

        foreach ($uniqKomoditi as $komoditi) {
            $all = Penyaluran::where('komoditi', $komoditi)->where('tahun', Carbon::now()->year)->where('periode',$periode)->count();
            $done = Penyaluran::where('komoditi', $komoditi)->where('tahun', Carbon::now()->year)->where('periode',$periode)->where('status_penyaluran', 1)->count();
            // dd($all, $done);
            $precentage = round(($done / $all) * 100);
            $dataPerBulan = array_map(function($bulan) use ($komoditi) {
                return Penyaluran::where('komoditi', $komoditi)->whereMonth('tgl_penyaluran', $bulan)->whereYear('tgl_penyaluran', Carbon::now()->year)->where('status_penyaluran', 1)->count();
            }, $uniqBulan);
            $series[] = ['name' => $komoditi, 'data' => $dataPerBulan];
            $progress[] = $precentage;

            $menunggu = Penyaluran::where('komoditi', $komoditi)->where('tahun', Carbon::now()->year)->where('periode',$periode)->where('status_penyaluran', 0)->count();
            $berhasil = Penyaluran::where('komoditi', $komoditi)->where('tahun', Carbon::now()->year)->where('periode',$periode)->where('status_penyaluran', 1)->count();
            $gagal = Penyaluran::where('komoditi', $komoditi)->where('tahun', Carbon::now()->year)->where('periode',$periode)->where('status_penyaluran', 2)->count();
            $sebagian = Penyaluran::where('komoditi', $komoditi)->where('tahun', Carbon::now()->year)->where('periode',$periode)->where('status_penyaluran', 3)->count();
            $donutData[] = [
                'komoditi' => $komoditi,
                'menunggu' => $menunggu,
                'berhasil' => $berhasil,
                'gagal' => $gagal,
                'disalurkan sebagian' => $sebagian
            ];

            $pengajuanmenunggu = Pengajuan::where('komoditi', $komoditi)->where('status_validasi', 0)->count();
            $pengajuanberhasil = Pengajuan::where('komoditi', $komoditi)->where('status_validasi', 1)->count();
            $pengajuangagal = Pengajuan::where('komoditi', $komoditi)->where('status_validasi', 2)->count();
$treemapData[] = [
        'name' => $komoditi,
        'data' => [
            ['x' => 'Menunggu', 'y' => $pengajuanmenunggu],
            ['x' => 'Berhasil', 'y' => $pengajuanberhasil],
            ['x' => 'Gagal',    'y' => $pengajuangagal],
        ]
    ];        }
        $role = Auth::User()->role;
        return $role == '0' ? view('poktan.dashboard', compact('user', 'data', 'label', 'progress','periodePenyaluran','donutData','treemapData'))->with('series', json_encode($series)) :
            ($role == '1' ? view('ppl.dashboard', compact('user', 'data', 'label', 'progress','periodePenyaluran','donutData','treemapData'))->with('series', json_encode($series)) : redirect()->back());
    }

    public function dashboardpetani(Request $request) 
    {
        if(Auth::guard('petani')->user()){
            $user = Auth::guard('petani')->user()->nik;
            $menunggu = Pengajuan::where('nik',$user)->where('status_validasi', 0)->count();
            $berhasil = Pengajuan::where('nik',$user)->where('status_validasi', 1)->count();
            $gagal = Pengajuan::where('nik',$user)->where('status_validasi', 2)->count();

            // Konversi ke persentase

            $lahan = Lahan::where('add_by',$user)->latest()->take(3)->get();

            
        // Ambil data unik untuk dropdown filter
            $tahunrdkk = Rdkk::select('tahun')->distinct()->get();
            $komoditirdkk = Rdkk::select('komoditi')->distinct()->get();
            $perioderdkk = Rdkk::select('periode')->distinct()->get();

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
            })->where('status_penyaluran', 1)
            ->orderBy('tahun', 'desc')
            ->get()
            ->groupBy('nik');

            
            $uniqueNamaPupuk = $rdkk->flatMap(function ($items) {
                return $items->pluck('pupuk.nama_pupuk');
            })->unique();
            
            
                // dd($data);


            return view('petani.dashboard', compact('menunggu','berhasil','gagal','lahan','rdkk', 'tahunrdkk', 'komoditirdkk', 'perioderdkk', 'tahun', 'periode', 'komoditi','uniqueNamaPupuk'));
        }
    }
}
