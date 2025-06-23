<?php

namespace App\Http\Controllers;

use App\Models\detailPenyaluranSebagian;
use App\Models\Lahan;
use App\Models\Penyaluran;
use App\Models\Pupuk;
use App\Models\Rdkk;
use App\Models\Stok;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyaluranController extends Controller
{
    //

    // public function cari(Request $request)
    // {
    //     $data = Rdkk::where('tahun', $request->tahun)
    //                 ->where('periode', $request->periode)
    //                 ->where('id_pupuk', $request->id_pupuk)
    //                 ->get();
    //     $volTotal = $data->sum('volume_pupuk_mt1') + 
    //                 $data->sum('volume_pupuk_mt2') + 
    //                 $data->sum('volume_pupuk_mt3');

    //     return response()->json($volTotal);
    // }


    public function index(Request $request)
    {

        $tahunrdkk = Rdkk::select('tahun')->distinct()->get();
        $perioderdkk = Rdkk::select('periode')->distinct()->get();

        $tahunPenyaluran = Penyaluran::select('tahun')->distinct()->get();
        $periodePenyaluran = Penyaluran::select('periode')->distinct()->get();

        $tahun = $request->input('tahun', Penyaluran::latest('created_at')->value('tahun'));
        $periode = $request->input('periode', Penyaluran::latest('created_at')->value('periode'));


        $Penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
                })
                ->when($periode, function ($query, $periode) {
                    return $query->where('periode', $periode);
                })
                ->orderBy('tahun', 'desc')
                ->get()
                ->groupBy('komoditi');

        $tanggalPenyaluran = $Penyaluran->first()?->first()->tgl_penyaluran ?? '-';
        $statusPenyaluran = [];
        foreach ($Penyaluran as $komoditi => $data) {
            $totalNik = $data->count(); // Jumlah nik berdasarkan komoditi
            $tersalurkan = ($data->where('status_penyaluran', 1)->count() + $data->where('status_penyaluran', 3)->count()); // Jumlah tersalurkan (status_penyaluran = 1)
    
            $statusPenyaluran[$komoditi] = "{$tersalurkan}/{$totalNik} menerima"; // Format seperti "2/3 tersalurkan"
        }

        $rdkkData = Rdkk::with(['pupuk'])
                    ->where('tahun', $tahun)
                    ->where('periode', $periode)
                    ->get();
        
        $details = detailPenyaluranSebagian::with(['pupuk'])
                                ->get();
                
        // Return view dengan data yang dibutuhkan
        return view('poktan.penyaluran', compact(
            'tanggalPenyaluran', 
            'Penyaluran', 
            'tahunPenyaluran', 
            'periodePenyaluran', 
            'tahun', 
            'periode',
            'tahunrdkk',  
            'perioderdkk',
            'statusPenyaluran','rdkkData','details' // Mengirimkan status penyaluran untuk ditampilkan
        ));
    }

    public function laporan(Request $request){
        $tahunrdkk = Rdkk::select('tahun')->distinct()->get();
        $tahun = $request->input('tahun', Penyaluran::latest('created_at')->value('tahun'));
        $periode = $request->input('periode', Penyaluran::latest('created_at')->value('periode'));

        $tahunFilter = $request->input('tahun_filter', Penyaluran::latest('created_at')->value('tahun'));
        $bulanFilter = $request->input('month', Carbon::now()->format('m'));
        $start= $request->input('start_date', 0);
        $end= $request->input('end_date', 0);

                $Penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
                })
                ->when($periode, function ($query, $periode) {
                    return $query->where('periode', $periode);
                })
                ->orderBy('tahun', 'desc')
                ->get()
                ->groupBy('komoditi');
        $penyaluranid = Penyaluran::where('tahun', $tahun)
                                ->where('periode', $periode)
                                ->get();
        
        if ($start==0 && $end==0){
            $filter_pembayaran = Penyaluran::when($tahun, function ($query, $tahun) {
                                                return $query->where('tahun', $tahun);
                                            })
                                            ->when($periode, function ($query, $periode) {
                                                return $query->where('periode', $periode);
                                            })
                                            ->whereYear('tgl_bayar', $tahunFilter)
                                            ->whereMonth('tgl_bayar', $bulanFilter)
                                            ->where('status_pembayaran', 1)
                                            ->orderBy('tahun', 'desc')
                                            ->get()
                                            ->groupBy('komoditi');
            $filter_penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
                                                return $query->where('tahun', $tahun);
                                            })
                                            ->when($periode, function ($query, $periode) {
                                                return $query->where('periode', $periode);
                                            })
                                            ->whereYear('tgl_penyaluran', $tahunFilter)
                                            ->whereMonth('tgl_penyaluran', $bulanFilter)
                                            ->where('status_penyaluran', 1)
                                            ->orderBy('tahun', 'desc')
                                            ->get()
                                            ->groupBy('komoditi');
            $detailPenyaluranSebagian = detailPenyaluranSebagian::with(['pupuk'])
                                            ->whereYear('created_at', $tahunFilter)
                                            ->whereMonth('created_at', $bulanFilter)
                                            ->get()
                                            ->groupBy('id_penyaluran');

        } else {
        $filter_pembayaran = Penyaluran::when($tahun, function ($query, $tahun) {
                        return $query->where('tahun', $tahun);
                    })
                    ->when($periode, function ($query, $periode) {
                        return $query->where('periode', $periode);
                    })
                    ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                        // Filter berdasarkan rentang tanggal
                        $query->whereBetween('tgl_bayar', [$request->input('start_date'), $request->input('end_date')]);
                    })
                    ->where('status_pembayaran', 1)
                    ->orderBy('tahun', 'desc')
                    ->get()
                    ->groupBy('komoditi');

        $filter_penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
                        return $query->where('tahun', $tahun);
                    })
                    ->when($periode, function ($query, $periode) {
                        return $query->where('periode', $periode);
                    })
                    ->whereBetween('tgl_penyaluran', [$start, $end])
                    ->where('status_penyaluran', 1)
                    ->orderBy('tahun', 'desc')
                    ->get()
                    ->groupBy('komoditi');
        $detailPenyaluranSebagian = detailPenyaluranSebagian::with(['pupuk'])
                                    ->whereBetween('created_at', [$start, $end])
                                    ->get()
                                    ->groupBy('id_penyaluran');

        }
        
        $rdkkData = Rdkk::with(['pupuk'])
        ->where('tahun', $tahun)
        ->where('periode', $periode)
        ->get();
        return view('poktan.penyalurans', compact(
            'tahun','periode','Penyaluran','rdkkData','tahunrdkk','filter_pembayaran','filter_penyaluran',
            'tahunFilter','bulanFilter','start','end','penyaluranid' , 'detailPenyaluranSebagian'
        ));

        
    }

    public function detailpenyaluran(Request $request){
        // POKTAN
        $request->validate([
            'tahun'=>'required',
            'periode'=>'required',
            'komoditi'=>'required',
        ]);

        $status = $request->input('status', 'all');

        $tahun = $request->tahun;
        $periode = $request->periode;
        $komoditi = $request->komoditi;

        $Penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })
        ->when($periode, function ($query, $periode) {
            return $query->where('periode', $periode);
        })
        ->when($komoditi, function ($query, $komoditi) {
            return $query->where('komoditi', $komoditi);
        })
        ->when($status !== 'all', function ($query) use ($status) {
            if ($status === '0') {
                return $query->where('status_penyaluran', 0);
            } elseif ($status === '2') {
                return $query->where('status_penyaluran', 2);
            } 
        })
        ->orderBy('tahun', 'desc')
        ->get();

        $rdkk = Rdkk::with(['pupuk'])->where('tahun', $tahun)
        ->where('periode', $periode)->where('komoditi', $komoditi)->get();

        $stok = Stok::with(['pupuk'])->where('tahun', $tahun)->where('periode', $periode)->get();
        $detailPenyaluranSebagian = detailPenyaluranSebagian::with(['pupuk'])->get();
        

        return view('poktan.detailpenyaluran', compact('Penyaluran', 'tahun', 'periode', 'komoditi','rdkk', 'stok', 'detailPenyaluranSebagian'));

    }

    public function show(Request $request)
    {
        $tahunPenyaluran = Penyaluran::select('tahun')->distinct()->get();
        $tahun = $request->input('tahun', Penyaluran::latest('created_at')->value('tahun'));
        $nik= Auth::guard('petani')->user()->nik;

        $penyaluran = Penyaluran::where('nik', $nik)
                        ->where('tahun', $tahun)->get();
        $stok = Stok::with(['pupuk'])->where('tahun', $tahun)->get()->groupBy('periode');

        $rdkk = Rdkk::with(['pupuk'])->where('nik',$nik)->where('tahun', $tahun)->get();
        return view ('petani.penyaluran', compact('penyaluran','rdkk','tahun','tahunPenyaluran', 'stok'));
    }


    public function detail(Request $request)
    {
        // PETANI
        $request->validate([
            'id'=>'required',
        ]);


        // dd($request->id);
        $tahunPenyaluran = Penyaluran::select('tahun')->distinct()->get();
        $tahun = $request->input('tahun', Penyaluran::latest('created_at')->value('tahun'));
        // $nik= Auth::guard('petani')->user()->nik;

        $penyaluran = Penyaluran::findOrFail($request->id);        // dd($penyaluran);

        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $penyaluran->total_bayar,
            ),
            'customer_details' => array(
               'first_name' => 'NIK Petani ' . $penyaluran->nik,
            ),'item_details' => [
                [
                    'id' => $penyaluran->id,
                    'price' => $penyaluran->total_bayar,
                    'quantity' => 1,
                    'name' => $penyaluran->komoditi . $penyaluran->tahun . '/' . $penyaluran->periode,
                ]
            ],

        );
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $penyaluran->snap_token = $snapToken;
        $penyaluran->save();

        $stok = Stok::with(['pupuk'])->where('tahun', $penyaluran->tahun)->where('periode', $penyaluran->periode)->get();

        $rdkk = Rdkk::with(['pupuk'])->where('nik',$penyaluran->nik)
                                    ->where('tahun', $penyaluran->tahun)
                                    ->where('periode', $penyaluran->periode)
                                    ->where('komoditi', $penyaluran->komoditi)->get();
        // dd($rdkk);
        $details = detailPenyaluranSebagian::with(['pupuk'])
                                    ->where('id_penyaluran', $penyaluran->id)
                                    ->get();
        return view ('petani.detailpenyaluran',compact('penyaluran','rdkk','tahun','tahunPenyaluran','stok','details'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'komoditi'=>'required',
            'periode'=>'required',
            'tahun'=>'required',
        ]);

        $rdkk = Rdkk::where('komoditi', $request->komoditi)
        ->where('periode', $request->periode)
        ->where('tahun', $request->tahun)->get();
        
        foreach($rdkk as $data){
            $data->status_penyaluran = 1;
            $data->save();
        }

        // dd($penyaluran);
        $nik = Rdkk::where('tahun', $request->tahun)
        ->where('periode', $request->periode)
        ->where('komoditi', $request->komoditi)->select('nik')->distinct()->get();

        if ($nik->isEmpty()) {
                return redirect()->back()->with('error', 'Data RDKK tidak ditemukan untuk kombinasi tahun, periode, dan komoditi tersebut.');
            }
        else{
            foreach ($nik as $item) {
                $total_bayar = Rdkk::where('nik', $item->nik)->where('tahun', $request->tahun)
                ->where('periode', $request->periode)
                ->where('komoditi', $request->komoditi)->sum('total_harga');
            
                $penyaluran = Penyaluran::where('nik', $item->nik)
                ->where('tahun', $request->tahun)
                ->where('periode', $request->periode)
                ->where('komoditi', $request->komoditi)
                ->first();
            
                    
            if ($penyaluran) {
                $penyaluran->update([
                    'tahun' => $request->tahun,
                    'periode' => $request->periode,
                    'komoditi' => $request->komoditi,
                    'nik' => $item->nik,
                    'total_bayar' => $total_bayar,
                    // 'snap_token'=>null,
                ]);
            } else {
                Penyaluran::create([
                    'tahun' => $request->tahun,
                    'periode' => $request->periode,
                    'komoditi' => $request->komoditi,
                    'nik' => $item->nik,
                    'total_bayar' => $total_bayar,

                ]);
            }
                
            }
        }
        return redirect()->route('rdkk.index')->with('success', 'Data penyaluran berhasil ditambahkan.');

    }

    public function bayaronline(Request $request,$id){
        $request->validate([
            'id'=>'required',
        ]);
        $penyaluran = Penyaluran::findOrFail($id);
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $penyaluran->total_bayar,
            ),
            'customer_details' => array(
               'first_name' => 'NIK Petani ' . $penyaluran->nik,
            ),'item_details' => [
                [
                    'id' => $penyaluran->id,
                    'price' => $penyaluran->total_bayar,
                    'quantity' => 1,
                    'name' => $penyaluran->komoditi . $penyaluran->tahun . '/' . $penyaluran->periode,
                ]
            ],

        );
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $penyaluran->snap_token = $snapToken;
        $penyaluran->save();

        return response()->json(['message' => 'Snap token updated']); // biar fetch() bisa lanjut

    }

    public function bayarsukses(Request $request,$id)
    {
        $penyaluran = Penyaluran::findOrFail($id);

        $rdkk=Rdkk::where('nik', $penyaluran->nik)
            ->where('tahun', $penyaluran->tahun)
            ->where('periode', $penyaluran->periode)
            ->where('komoditi', $penyaluran->komoditi)
            ->where('status_penyaluran', 1)->get();
        
        foreach ($rdkk as $data) {
            $volume = $data->volume_pupuk_mt1 + $data->volume_pupuk_mt2 + $data->volume_pupuk_mt3;
            $stok = Stok::where('id_pupuk', $data->id_pupuk)
                        ->where('tahun', $penyaluran->tahun)
                        ->where('periode', $penyaluran->periode)
                        ->first();
            if ($stok) {
                $stoktersisa = $stok->pupuk_tersisa - $volume;
                if ($stoktersisa < 0) {
                    return redirect()->back()->with('error', 'Stok pupuk tidak mencukupi untuk penyaluran ini.');
                }
                $stok->pupuk_tersisa = $stoktersisa; // Update stok tersisa sesuai dengan volume yang disalurkan
                $stok->save();
            } else {
                return redirect()->back()->with('error', 'Stok pupuk tidak ditemukan untuk penyaluran ini.');   
            }
        }


        $penyaluran->metode_bayar = 'online';
        $penyaluran->status_pembayaran = 1;
        $penyaluran->tgl_bayar= Carbon::now();
        $penyaluran->save();

        return view('petani.sukses', compact('penyaluran'));
    }

    public function konfirmasi(Request $request)
    {
        $request->validate([
        'id'=>'required',
        'status_penyaluran'=>'required',
        ]);    

        $penyaluran = Penyaluran::findOrFail($request->id);

        $rdkk=Rdkk::where('nik', $penyaluran->nik)
            ->where('tahun', $penyaluran->tahun)
            ->where('periode', $penyaluran->periode)
            ->where('komoditi', $penyaluran->komoditi)
            ->where('status_penyaluran', 1)->get();

        if ($request->status_penyaluran == 1) {
        $dataBaru = $request->input('dataBaru'); 
            foreach ($dataBaru as $data) {
                $id = $data['id'];
                $voluPupuk= $data['voluPupuk'];
                $stok = Stok::where('id_pupuk', $id)
                            ->where('tahun', $penyaluran->tahun)
                            ->where('periode', $penyaluran->periode)
                            ->first();
                if ($stok) {
                    if ($voluPupuk > $stok->pupuk_tersisa) {
                        return redirect()->back()->with('error', 'Stok pupuk tidak mencukupi untuk penyaluran sepenuhnya.');
                    }
                }
            }
        $penyaluran->tgl_penyaluran = Carbon::now();
        $penyaluran->status_penyaluran = 1;
        $penyaluran->info= "Diterima Petani";
        if ($penyaluran->status_pembayaran == 0) {
            foreach ($rdkk as $data) {
                $volume = $data->volume_pupuk_mt1 + $data->volume_pupuk_mt2 + $data->volume_pupuk_mt3;
                $stok = Stok::where('id_pupuk', $data->id_pupuk)
                            ->where('tahun', $penyaluran->tahun)
                            ->where('periode', $penyaluran->periode)
                            ->first();
                if ($stok) {
                    $stoktersisa = $stok->pupuk_tersisa - $volume;
                    if ($stoktersisa < 0) {
                        return redirect()->back()->with('error', 'Stok pupuk tidak mencukupi untuk penyaluran ini.');
                    }
                    $stok->pupuk_tersisa = $stoktersisa; // Update stok tersisa sesuai dengan volume yang disalurkan
                    $stok->save();
                } else {
                    return redirect()->back()->with('error', 'Stok pupuk tidak ditemukan untuk penyaluran ini.');   
                }
            }

            $penyaluran->status_pembayaran=1;
            $penyaluran->metode_bayar="offline";
            $penyaluran->tgl_bayar= Carbon::now();
        }
        $penyaluran->save();

        return redirect()->route('penyaluran.index')->with('success', 'Penyaluran Terkonfirmasi.');
        }elseif ($request->status_penyaluran == 2) {
            $stok = Stok::where('tahun', $penyaluran->tahun)
                        ->where('periode', $penyaluran->periode)
                        ->sum('pupuk_tersisa');
            if ($stok > 0) {
                return redirect()->back()->with('error', 'Stok pupuk masih tersedia, tidak dapat menandai penyaluran sebagai gagal.');
            }

            $penyaluran->status_penyaluran = 2;
            $penyaluran->status_pembayaran=2;
            $penyaluran->metode_bayar="-";
            $penyaluran->tgl_bayar= null;

            $penyaluran->info = "Pupuk Habis";

            $penyaluran->save();
            return redirect()->route('penyaluran.index')->with('error', 'Penyaluran Gagal');
        }else {
        $request->validate([
                    'pupuk' => 'required|array',
                    'pupuk.*' => 'required|exists:pupuk,id',
                    'vol' => 'required|array',
                    'vol.*' => 'required|numeric|min:0',
        ]);  
        $pupuk_ids = $request->input('pupuk');
        $vol = $request->input('vol');
        $stok = Stok::where('tahun', $penyaluran->tahun)
            ->where('periode', $penyaluran->periode)
            ->sum('pupuk_tersisa');
        if ($stok <= 0) {   
            return redirect()->back()->with('error', 'Tidak ada pupuk tersisa.');
        }

        $penyaluran->status_penyaluran = 3; // Status penyaluran belum terkonfirmasi
        if ($penyaluran->status_pembayaran == 0) {
            $penyaluran->status_pembayaran=1;
            $penyaluran->metode_bayar="offline";
            $penyaluran->tgl_bayar= Carbon::now();
            $penyaluran->tgl_penyaluran= Carbon::now();
        }
        $penyaluran->save();
        $volpenyaluran=0;
        foreach ($pupuk_ids as $index => $pupuk_id) {
            // Validasi apakah pupuk_id dan volume_pupuk sesuai
            if (isset($vol[$index]) && is_numeric($vol[$index]) && $vol[$index] >= 0) {
            $stok = Stok::where('id_pupuk', $pupuk_id)
                ->where('tahun', $penyaluran->tahun)
                ->where('periode', $penyaluran->periode)
                ->first();
            
                if ($stok) {
                    $stoktersisa = $stok->pupuk_tersisa - $vol[$index];
                    if ($stoktersisa < 0) {
                        return redirect()->back()->with('error', 'Stok pupuk tidak mencukupi untuk penyaluran ini.');
                    }
                    $stok->pupuk_tersisa = $stoktersisa; // Update stok tersisa sesuai dengan volume yang disalurkan
                    $stok->save();
                } else {
                    return redirect()->back()->with('error', 'Stok pupuk tidak ditemukan untuk penyaluran ini.');   
                }

                // Simpan detail penyaluran sebagian
                detailPenyaluranSebagian::create([
                    'id_penyaluran' => $penyaluran->id,
                    'id_pupuk' => $pupuk_id,
                    'volume_pupuk' => $vol[$index],
                ]);
            } else {
                return redirect()->back()->with('error', 'Volume pupuk tidak valid.');
            }
        }
        return redirect()->route('penyaluran.index')->with('success', 'Penyaluran Terkonfirmasi.');
        }

    }

    // public function berhasil(Request $request){
    //     $request->validate([
    //         'id'=>'required',
    //     ]);    

    //     $penyaluran = Penyaluran::findOrFail($request->id);
    //     $penyaluran->tgl_penyaluran = Carbon::now();
    //     $penyaluran->status_penyaluran = 1;
    //     $penyaluran->info= "Diterima Petani";
    //     if ($penyaluran->status_pembayaran == 0) {
    //         $penyaluran->status_pembayaran=1;
    //         $penyaluran->metode_bayar="offline";
    //         $penyaluran->tgl_bayar= Carbon::now();
    //     }
    //     $penyaluran->save();
    //     return redirect()->route('penyaluran.index')->with('success', 'Penyaluran Terkonfirmasi.');

    // }

    // public function gagal(Request $request){
    //     $request->validate([
    //         'id'=>'required',
    //         'info'=>'required',
    //     ]);    

    //     $penyaluran = Penyaluran::findOrFail($request->id);
    //     $penyaluran->status_penyaluran = 2;
    //     $penyaluran->status_pembayaran=2;
    //     $penyaluran->metode_bayar="-";
    //     $penyaluran->tgl_bayar= null;

    //     $penyaluran->info = $request->info;

    //     $penyaluran->save();
    //     return redirect()->route('penyaluran.index')->with('error', 'Penyaluran Gagal');

    // }

    public function report(Request $request)
    {
        $tahun = $request->input('tahun', Penyaluran::latest('created_at')->value('tahun'));
        $periode = $request->input('periode', Penyaluran::latest('created_at')->value('periode'));

        $Penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })
        ->when($periode, function ($query, $periode) {
            return $query->where('periode', $periode);
        })
        ->orderBy('tahun', 'desc')
        ->get()
        ->groupBy('komoditi');

        $tanggalPenyaluran = $Penyaluran->first()?->first()->tgl_penyaluran ?? '-';
        $statusPenyaluran = [];
        foreach ($Penyaluran as $komoditi => $data) {
            $totalNik = $data->count(); // Jumlah nik berdasarkan komoditi
            $tersalurkan = $data->where('status_penyaluran', 1)->count(); // Jumlah tersalurkan (status_penyaluran = 1)
    
            $statusPenyaluran[$komoditi] = "{$tersalurkan}/{$totalNik} tersalurkan"; // Format seperti "2/3 tersalurkan"
        }

        $rdkkData = Rdkk::with(['pupuk'])
                    ->where('tahun', $tahun)
                    ->where('periode', $periode)
                    ->get();


        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);                    
        $options->set('isPhpEnabled', true); // Aktifkan PHP untuk placeholder
        // Return view dengan data yang dibutuhkan
        $pdf = new Dompdf($options);
        $pdf = Pdf::loadView('pdf.Penyaluranreport', compact(
            'tanggalPenyaluran', 
            'Penyaluran', 
            'tahun', 
            'periode',
            'statusPenyaluran','rdkkData' // Mengirimkan status penyaluran untuk ditampilkan
        ));
        return $pdf->stream();
    }   
    
    public function reports(Request $request)
    {
        $request->validate([
            'tahun'=>'required',
            'periode'=>'required',
            'komoditi'=>'required',
        ]);


        $tahun = $request->tahun;
        $periode = $request->periode;
        $komoditi = $request->komoditi;

        $Penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })
        ->when($periode, function ($query, $periode) {
            return $query->where('periode', $periode);
        })
        ->when($komoditi, function ($query, $komoditi) {
            return $query->where('komoditi', $komoditi);
        })
        ->when($request->has('status') && $request->status !== 'all', function ($query) use ($request) {
            if ($request->status === '0') {
                return $query->where('status_penyaluran', 0);
            } elseif ($request->status === '2') {
                return $query->where('status_penyaluran', 2);
            } 
        })
        ->orderBy('tahun', 'desc')
        ->get();

        $rdkk = Rdkk::with(['pupuk'])->where('tahun', $tahun)
        ->where('periode', $periode)->where('komoditi', $komoditi)->get();

        

        $pdf = Pdf::loadView('pdf.Penyaluranreports', compact('Penyaluran', 'tahun', 'periode', 'komoditi','rdkk'));
        return $pdf->stream();
    }

    public function info(Request $request)
    {
        $request->validate([
            'tahun'=>'required',
            'periode'=>'required',
            'table'=>'required',
        ]);    

        $tahun = $request->tahun;
        $periode = $request->periode;
        $table = $request->table;
        $tahunFilter =$request->tahun_filter;
        $bulanFilter = $request->month;
        $start = $request->input('start_date');
        $end = $request->input('end_date');


        $Penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
                })
                ->when($periode, function ($query, $periode) {
                    return $query->where('periode', $periode);
                })
                ->orderBy('tahun', 'desc')
                ->get()
                ->groupBy('komoditi');

        $penyaluranid = Penyaluran::where('tahun', $tahun)
                    ->where('periode', $periode)
                    ->get();


        if ($start==0 && $end==0){
            $filter_pembayaran = Penyaluran::when($tahun, function ($query, $tahun) {
                                                return $query->where('tahun', $tahun);
                                            })
                                            ->when($periode, function ($query, $periode) {
                                                return $query->where('periode', $periode);
                                            })
                                            ->when($request->has('month') && $request->has('tahun_filter'), function ($query) use ($request) {
                                                // Filter berdasarkan bulan dan tahun
                                                $query->whereYear('tgl_bayar', $request->input('tahun_filter'))
                                                    ->whereMonth('tgl_bayar', $request->input('month'));
                                            })
                                            ->where('status_pembayaran', 1)
                                            ->orderBy('tahun', 'desc')
                                            ->get()
                                            ->groupBy('komoditi');

                                $filter_penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
                                                return $query->where('tahun', $tahun);
                                            })
                                            ->when($periode, function ($query, $periode) {
                                                return $query->where('periode', $periode);
                                            })
                                            ->when($request->has('month') && $request->has('tahun_filter'), function ($query) use ($request) {
                                                // Filter berdasarkan bulan dan tahun
                                                $query->whereYear('tgl_penyaluran', $request->input('tahun_filter'))
                                                    ->whereMonth('tgl_penyaluran', $request->input('month'));
                                            })
                                            ->where('status_penyaluran', 1)
                                            ->orderBy('tahun', 'desc')
                                            ->get()
                                            ->groupBy('komoditi');
            $detailPenyaluranSebagian = detailPenyaluranSebagian::with(['pupuk'])
                                            ->whereYear('created_at', $tahunFilter)
                                            ->whereMonth('created_at', $bulanFilter)
                                            ->get()
                                            ->groupBy('id_penyaluran');


        } else {
                    $filter_pembayaran = Penyaluran::when($tahun, function ($query, $tahun) {
                        return $query->where('tahun', $tahun);
                    })
                    ->when($periode, function ($query, $periode) {
                        return $query->where('periode', $periode);
                    })
                    ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                        // Filter berdasarkan rentang tanggal
                        $query->whereBetween('tgl_bayar', [$request->input('start_date'), $request->input('end_date')]);
                    })
                    ->where('status_pembayaran', 1)
                    ->orderBy('tahun', 'desc')
                    ->get()
                    ->groupBy('komoditi');

        $filter_penyaluran = Penyaluran::when($tahun, function ($query, $tahun) {
                        return $query->where('tahun', $tahun);
                    })
                    ->when($periode, function ($query, $periode) {
                        return $query->where('periode', $periode);
                    })
                    ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                        // Filter berdasarkan rentang tanggal
                        $query->whereBetween('tgl_penyaluran', [$request->input('start_date'), $request->input('end_date')]);
                    })
                    ->where('status_penyaluran', 1)
                    ->orderBy('tahun', 'desc')
                    ->get()
                    ->groupBy('komoditi');
        $detailPenyaluranSebagian = detailPenyaluranSebagian::with(['pupuk'])
                                    ->whereBetween('created_at', [$start, $end])
                                    ->get()
                                    ->groupBy('id_penyaluran');

        }
        
        $rdkkData = Rdkk::with(['pupuk'])
        ->where('tahun', $tahun)
        ->where('periode', $periode)
        ->get();

        $stok = Stok::with(['pupuk'])
        ->where('tahun', $tahun)
        ->where('periode', $periode)
        ->get();
        $pdf = Pdf::loadView('pdf.Inforeport', compact(
            'tahun', 
            'periode',
            'filter_pembayaran','filter_penyaluran','rdkkData','Penyaluran',
            'tahunFilter','bulanFilter','start','end','table','penyaluranid' , 'detailPenyaluranSebagian','stok')); // Mengirimkan status penyaluran untuk ditampilkan));
        return $pdf->stream();


    }

    

}
