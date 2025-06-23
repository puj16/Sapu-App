@extends('layouts.masterpetugas')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif



<div class="title-group mb-3 d-flex align-items-center">
    <h1 class="h2 mb-0">Data Penyaluran</h1>
</div>

<div class="row my-4">

    <div class="col-lg-12 col-12">
        <div class="custom-block bg-white p-3">
            <div class="row align-items-center">
                    <div class="col-lg-6 col-12">
                        <p class=" mt-3 mb-4">
                            <strong class="me-2">Informasi</strong>
                        <br>
                            <span>Pembayaran</span>
                            <span class="ms-1 me-1">&</span>
                            <span>Penyaluran</span>
                        </p>
                        @if ($start==0&&$end==0)
                ({{ str_pad($bulanFilter, 2, '0', STR_PAD_LEFT) }} - {{ $tahunFilter }}) 
                @else
                {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}   
                @endif
                        @php
                            $firstItem = $Penyaluran->first();
                        @endphp 
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-end">
                        <div class="position-absolute top-0 end-0 p-3">
                            <button type="button" class="btn " data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="bi bi-calendar-range"></i>
                            </button>                        
                        </div>
                    </div>
                    
                    <div class="col-lg-12 col-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="true">Pembayaran</button>
                            </li>
            
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab" aria-controls="password-tab-pane" aria-selected="false">Penyaluran</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification-tab-pane" type="button" role="tab" aria-controls="notification-tab-pane" aria-selected="false">Penyaluran Sebagian</button>
                            </li>

            
                        </ul>
            
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                @if ($filter_pembayaran->isEmpty())
                                    <div class="col-lg-6 col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <p class="mb-4 text-black">Data tidak tersedia</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex">
                                        <h6 class="mb-4 text-align-center">Pupuk Terbayar</h6>
                                        <div id="customButtonWrapper1" class="d-inline-block ms-2">
                                            @if(auth()->user()->role == 0)
                                            <form action="{{ route('penyaluran.info') }}" method="get">
                                                @csrf
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <input type="hidden" name="tahun_filter" value="{{ $tahunFilter }}">
                                                <input type="hidden" name="month" value="{{ $bulanFilter }}">
                                                <input type="hidden" name="start_date" id="" value="{{ $start }}">
                                                <input type="hidden" name="end_date" value="{{ $end }}">
                                                <input type="hidden" name="table" value="1">
                                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                                            </form>
                                            @elseif(auth()->user()->role == 1)
                                            <form action="{{ route('info.penyaluran') }}" method="get">
                                                @csrf
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <input type="hidden" name="tahun_filter" value="{{ $tahunFilter }}">
                                                <input type="hidden" name="month" value="{{ $bulanFilter }}">
                                                <input type="hidden" name="start_date" id="" value="{{ $start }}">
                                                <input type="hidden" name="end_date" value="{{ $end }}">
                                                <input type="hidden" name="table" value="1">
                                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                                            </form>
                                            @endif
                                        </div>
        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="account-table table table-bordered" id="spembayaranTable">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Komoditi</th>
                                                    <th scope="col">Status Penyaluran</th>
                                                    <th scope="col">NIK Penerima</th>
                                                    <th scope="col">Terbayar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 1;
                                                @endphp
                                                @foreach ($filter_pembayaran as $komoditi=>$pembayaran)
                                                @php
                                                    $firstItem = $pembayaran->first(); // Ambil item pertama dalam grup
                                                    $jmlhNIK = $pembayaran->pluck('nik')->unique()->count();
                                                    $nikList = $pembayaran->pluck('nik')->unique();
                                                    $totalterbayar = $pembayaran->where('status_pembayaran',1)->sum('total_bayar');
                                                @endphp
                                                @foreach ($nikList as $index => $nik)
                                                    <tr style="background: white">
                                                        @if ($index === 0)
                                                            <td scope="row" rowspan="{{ $jmlhNIK }}">{{ $counter}}</td>
                                                            <td rowspan="{{ $jmlhNIK }}">{{ $firstItem->komoditi }}</td>
                                                        @endif
                                                        @php
                                                            $terbayarItem = $pembayaran->where('nik', $nik)->first(); // Ambil elemen pertama dari koleksi
                                                        @endphp

                                                        <td>{{ $terbayarItem->tgl_bayar }}</td>
                                                        <td>{{ $nik }}</td>
                                                        <td>                                                        
                                                            @if ($terbayarItem && $terbayarItem->status_pembayaran == 1)
                                                                {{ number_format($terbayarItem->total_bayar, 0, ',', '.') }}
                                                            @else
                                                                -
                                                            @endif                                            
                                                        </td>
                                                    </tr>
                                                @endforeach 
                                                @php
                                                    $counter++; // Tingkatkan counter setelah setiap grup selesai
                                                @endphp
            
                                                <tr style="background:#ececec">
                                                    <td colspan="4">Total</td>
                                                    <td>Rp{{ number_format($totalterbayar, 0, ',', '.') }}  </td>
                                                </tr>
                                                @foreach ($Penyaluran as $komoditi=>$detail)
                                                        @if ($komoditi == $firstItem->komoditi)
                                                            @php
                                                                $totals = $detail->sum('total_bayar');
                                                                $belumterbayar = $totals - ($detail->where('status_pembayaran',1)->sum('total_bayar'));
                                                            @endphp
                                                        @endif
                                                @endforeach
                                                <tr style="background: #ececec">
                                                    <td colspan="4">Total Belum Terbayar</td>
                                                    
                                                    <td>Rp{{ number_format($belumterbayar, 0, ',', '.') }}  </td>

                                                </tr>
                                                <tr style="background: #ececec">
                                                    <td colspan="4">Total Keseluruhan</td>
                                                    
                                                    <td>Rp{{ number_format($totals, 0, ',', '.') }}  </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
            
                            <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab" tabindex="0">
                                @if ($filter_penyaluran->isEmpty())
                                    <div class="col-lg-6 col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <p class="mb-4 text-black">Data tidak tersedia</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex">
                                        <h6 class="mb-4 text-align-center">Pupuk Diterima Petani </h6>
                                        <div id="customButtonWrapper1" class="d-inline-block ms-2">
                                            @if(auth()->user()->role == 0)
                                            <form action="{{ route('penyaluran.info') }}" method="get">
                                                @csrf
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <input type="hidden" name="tahun_filter" value="{{ $tahunFilter }}">
                                                <input type="hidden" name="month" value="{{ $bulanFilter }}">
                                                <input type="hidden" name="start_date" id="" value="{{ $start }}">
                                                <input type="hidden" name="end_date" value="{{ $end }}">
                                                <input type="hidden" name="table" value="2">
                                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                                            </form>
                                            @elseif(auth()->user()->role == 1)
                                            <form action="{{ route('info.penyaluran') }}" method="get">
                                                @csrf
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <input type="hidden" name="tahun_filter" value="{{ $tahunFilter }}">
                                                <input type="hidden" name="month" value="{{ $bulanFilter }}">
                                                <input type="hidden" name="start_date" id="" value="{{ $start }}">
                                                <input type="hidden" name="end_date" value="{{ $end }}">
                                                <input type="hidden" name="table" value="2">
                                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                                            </form>
                                            @endif
                                        </div>
        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="account-table table table-bordered" id="spembayaranTable">
                                            <thead>
                                                @php
                                                $pupukIds = $rdkkData->pluck('id_pupuk')
                                                                    ->unique();

                                                @endphp
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Komoditi</th>
                                                    <th scope="col">Tanggal Penyaluran</th>
                                                    <th scope="col">NIK Penerima</th>
                                                    @foreach ($pupukIds as $item)
                                                        @php
                                                            $pupuk = \App\Models\Pupuk::findOrFail($item);
                                                        @endphp
                                                    <th scope="col">{{ $pupuk->nama_pupuk }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 1;
                                                @endphp
                                                @foreach ($filter_penyaluran as $komoditi=>$penyaluran)
                                                @php
                                                    $firstItem = $penyaluran->first(); // Ambil item pertama dalam grup
                                                    $jmlhNIK = $penyaluran->pluck('nik')->unique()->count();
                                                    $nikList = $penyaluran->pluck('nik')->unique();
                                                    $totaltersalurkan = $penyaluran->where('status_penyaluran',1)->count('nik');

                                                    
                                                @endphp
                                                @foreach ($nikList as $index => $nik)
                                                    <tr>
                                                        @if ($index === 0)
                                                            <td scope="row" rowspan="{{ $jmlhNIK }}">{{ $counter}}</td>
                                                            <td rowspan="{{ $jmlhNIK }}">{{ $firstItem->komoditi }}</td>
                                                        @endif
                                                        @php
                                                            $tersalurkanItem = $penyaluran->where('nik', $nik)->first(); // Ambil elemen pertama dari koleksi
                                                        @endphp

                                                        <td>{{ $tersalurkanItem->tgl_penyaluran }}</td>
                                                        <td>{{ $nik }}</td>
                                                        @foreach ($pupukIds as $item)
                                                        @php
                                                            $volindividu = $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$nik)->sum('volume_pupuk_mt1')
                                                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$nik)->sum('volume_pupuk_mt2')
                                                                            +$rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$nik)->sum('volume_pupuk_mt3');
                                                        @endphp
        
                                                        <td>{{ $volindividu }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach 
                                                @php
                                                    $counter++; // Tingkatkan counter setelah setiap grup selesai
                                                @endphp
            
                                                <tr>
                                                    <td colspan="4">Total</td>
                                                    @foreach ($pupukIds as $item)
                                                        @php
                                                           $niktersalur = $penyaluran->where('status_penyaluran',1)->pluck('nik') ->unique();
                                                            $voltersalur =0;
                                                            foreach ($niktersalur as $n) {
                                                                $volpernik= $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt1') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt2') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt3');
                                                                        $voltersalur +=$volpernik;
                                                            }
                                                        @endphp
        
                                                        <td>{{ $voltersalur}}</td>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    <td colspan="4">Total Tersalurkan</td>
                                                    @foreach ($pupukIds as $item)
                                                        @php
                                                        
                                                           $niktersalur = App\Models\Penyaluran::where('komoditi', $firstItem->komoditi)->where('status_penyaluran',1)->pluck('nik') ->unique();
                                                        //    dd($niktersalur);
                                                            $voltersalurkan =0;
                                                            foreach ($niktersalur as $n) {
                                                                $volpernik= $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt1') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt2') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt3');
                                                                        $voltersalurkan +=$volpernik;
                                                            }
                                                        @endphp
        
                                                        <td>{{ $voltersalurkan}}</td>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    <td colspan="4">Total Keseluruhan Penerima Penyaluran</td>
                                                    @foreach ($pupukIds as $item)
                                                        @php
                                                             $volume = $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt1') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt2') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt3');
                                                           
                                                        @endphp
                                                        <td>{{ $volume }}</td>
                                                    @endforeach
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div>
            
                            <div class="tab-pane fade" id="notification-tab-pane" role="tabpanel" aria-labelledby="notification-tab" tabindex="0">
                                @if ($detailPenyaluranSebagian->isEmpty())
                                    <div class="col-lg-6 col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <p class="mb-4 text-black">Data tidak tersedia</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex">
                                        <h6 class="mb-4 text-align-center">Penyaluran Sebagian</h6>
                                        <div id="customButtonWrapper1" class="d-inline-block ms-2">
                                            @if(auth()->user()->role == 0)
                                            <form action="{{ route('penyaluran.info') }}" method="get">
                                                @csrf
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <input type="hidden" name="tahun_filter" value="{{ $tahunFilter }}">
                                                <input type="hidden" name="month" value="{{ $bulanFilter }}">
                                                <input type="hidden" name="start_date" id="" value="{{ $start }}">
                                                <input type="hidden" name="end_date" value="{{ $end }}">
                                                <input type="hidden" name="table" value="3">
                                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                                            </form>
                                            @elseif(auth()->user()->role == 1)
                                            <form action="{{ route('info.penyaluran') }}" method="get">
                                                @csrf
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <input type="hidden" name="tahun_filter" value="{{ $tahunFilter }}">
                                                <input type="hidden" name="month" value="{{ $bulanFilter }}">
                                                <input type="hidden" name="start_date" id="" value="{{ $start }}">
                                                <input type="hidden" name="end_date" value="{{ $end }}">
                                                <input type="hidden" name="table" value="3">
                                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                                            </form>
                                            @endif
                                        </div>
        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="account-table table table-bordered">
                                            <thead>
                                                @php
                                                    $pupukIds = $rdkkData->pluck('id_pupuk')
                                                                        ->unique();
        
                                                @endphp
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Komoditi</th>
                                                    <th scope="col">Tanggal Penyaluran</th>
                                                    <th scope="col">NIK Penerima</th>
                                                    @foreach ($pupukIds as $item)
                                                        @php
                                                            $pupuk = \App\Models\Pupuk::findOrFail($item);
                                                        @endphp
                                                    <th scope="col">{{ $pupuk->nama_pupuk }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($detailPenyaluranSebagian as $id_penyaluran => $data)
                                                @php
                                                    $firstItem = $data->first(); // Ambil item pertama dalam grup
                                                @endphp
                                                <tr>
                                                    <td>{{$loop->iteration }}</td>
                                                    <td>
                                                        @php
                                                        $record = $penyaluranid->firstWhere('id', $firstItem->id_penyaluran);
                                                        $komoditi = $record?->komoditi;
                                                        @endphp
                                                        {{ $record?->komoditi;}}
                                                    </td>
                                                    <td>{{ $firstItem->created_at }}</td>
                                                    <td>{{ $record?->nik }}</td>
                                                    @foreach ($pupukIds as $id)
                                                        @php
                                                            $detail = $data->firstWhere('id_pupuk', $id);
                                                        @endphp
                                                        <td>{{ $detail?->volume_pupuk ?? 0 }}</td>
                                                    @endforeach
                                                </tr>                                                    
                                                @endforeach

                                            </tbody>
                                        </table>
                                        @endif
                                    </div>

                        </div>
                        
                    </div>
            </div>
        </div>

   


    </div>
</div>


<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header model-filter text-white">
                <h5 class="modal-title" id="filterModalLabel">Filter Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs Navigation -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <ul class="nav nav-pills" id="filterTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-outline-primary" id="range-tab" data-bs-toggle="tab" data-bs-target="#range" type="button" role="tab" aria-controls="range" aria-selected="true">
                                <i class="bx bx-calendar"></i> Rentang Tanggal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-outline-primary" id="month-tab" data-bs-toggle="tab" data-bs-target="#month" type="button" role="tab" aria-controls="month" aria-selected="false">
                                <i class="bx bx-calendar-event"></i> Per Bulan
                            </button>
                        </li>
                    </ul>
                    <a href="" class="btn btn-reset">
                        <i class="bx bx-reset" style="font-size: 24px;"></i>
                    </a>
                </div>
                <!-- Tabs Content -->
                <div class="tab-content p-3 border rounded">
                    <!-- Rentang Tanggal -->
                    <div class="tab-pane fade show active" id="range" role="tabpanel" aria-labelledby="range-tab">
                        @if (auth()->user()->role==0)
                        <form method="GET" action="{{ route('penyaluran.lap') }}">
                        @elseif (auth()->user()->role==1)
                        <form method="GET" action="{{ route('lap.penyaluran') }}">
                        @endif
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label fw-bold">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control border-primaryy shadow-sm" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label fw-bold">Tanggal Akhir</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control border-primaryy shadow-sm" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary shadow-sm"><i class=""></i> Terapkan</button>
                            </div>
                        </form>
                    </div>

                    <!-- Per Bulan -->
                    <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">
                        @if (auth()->user()->role==0)
                        <form method="GET" action="{{ route('penyaluran.lap') }}">
                        @elseif (auth()->user()->role==1)
                        <form method="GET" action="{{ route('lap.penyaluran') }}">
                        @endif
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="month" class="form-label fw-bold">Bulan</label>
                                    <select name="month" id="month" class="form-select border-primary shadow-sm">
                                        @php
                                            $currentMonth = request('month', \Carbon\Carbon::now()->month); // Gunakan bulan saat ini jika tidak ada request
                                        @endphp
                                        @foreach (range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>                                </div>
                                <div class="col-md-6">
                                    <label for="year" class="form-label fw-bold">Tahun</label>
                                    <select name="tahun_filter" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary shadow-sm"><i class="bx bx_filter-alt"></i> Terapkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $(function() {
        $("#datepicker").datepicker({
            dateFormat: "dd/mm/yy", // Format tampilan
            changeMonth: true,
            changeYear: true
        });

        // Pakai bahasa Indonesia (opsional)
        $.datepicker.setDefaults($.datepicker.regional["id"]);
    });
</script>
<script>
    $(document).ready(function () {
        var table = $('#scheduleTable').DataTable({
            "paging": true,        // Mengaktifkan pagination
            "searching": true,     // Mengaktifkan fitur pencarian
            "ordering": true,      // Mengaktifkan pengurutan
            "lengthMenu": [5, 10, 25], // Menentukan jumlah data per halaman
            "language": {
                "search": "", // Menghapus label "Cari:"
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Mengubah input pencarian menjadi placeholder
        $('#scheduleTable_filter input').attr('placeholder', 'Search');
        $('#scheduleTable_filter').append($('#customButtonWrapper'));

    });
</script>



    
@endpush