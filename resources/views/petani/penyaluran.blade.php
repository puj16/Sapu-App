@extends('layouts.masterpetani')
@section('header')

    <link href="assets/img/logo.jpg" rel="icon">
    <!-- CSS FILES -->      
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/bootstrap-icons.css" rel="stylesheet">

    <link href="assets/css/apexcharts.css" rel="stylesheet">

    <link href="assets/css/tooplate-mini-finance.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
@endsection
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

<div class="row my-4">
    <div class="title-group mb-3 d-flex align-items-center">
        <div class="col-lg-9 col-md-4 col-sm-12 text-center text-md-start">
            <h1 class="h2 mb-0 ">Penyaluran {{ $tahun }}</h1>
        </div>
        <div class="d-flex justify-content-end col-lg-3 col-md-6 col-sm-12">
            <form method="GET" action="{{ route('rdkk.index') }}" class="row g-2 d-flex justify-content-end">
                <!-- Dropdown Tahun -->
                <div class="">
                    <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($tahunPenyaluran->pluck('tahun')->unique()->sortDesc() as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row my-4">
    <div class="col-lg-12 col-12">
        <div class="custom-block custom-block-exchange">
            <h6 class="mb-4">Stok Pupuk</h6>

            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center">
                    <img src="{{ asset ('assets/img/datang.png')}}" class="exchange-image img-fluid" alt="">

                    <div>
                        <p>Pupuk Datang</p>
                        <h6>Jumlah pupuk yang datang/diterima Poktan</h6>
                    </div>
                </div>

                <div class="ms-auto">
                </div>

                <div class="row ms-auto">
                    @foreach ($stok as $periode =>$data)
                    @php
                        $firstItem = $data->first();
                        $stoktersisa= $data->sum('pupuk_tersisa'); // Hitung total stok datang                     
                    @endphp
                    <div class="col-lg-12 col-12 d-flex justify-content-end">
                        <div class="me-4">
                            <small>Periode</small>
                            <h6>{{ $firstItem->periode }}</h6>
                        </div>
                        @if ($stoktersisa == 0)
                        <div class="me-4"> 
                            <small>Pupuk Habis</small>
                            <h6>Jika belum mendapatkan pupuk anda, <br> mohon maaf penyaluran tidak dapatdilanjutkan</h6>
                        </div>
                        @else
                        @foreach ($data as $item)
                        <div class="me-4">
                            <small>{{ $item->pupuk->nama_pupuk }} (Kg)</small>
                            <h6>{{ number_format($item->pupuk_datang, 0, ',', '.') }}</h6>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    @endforeach

                </div>
            </div>

            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center">
                    <img src="{{ asset ('assets/img/tersisa.png')}}" class="exchange-image img-fluid" alt="">

                    <div class="me-1">
                        <p>Pupuk Tersisa</p>
                        <h6>Jumlah pupuk belum dibayar/ditebus</h6>
                    </div>
                </div>

                <div class="ms-auto">
                </div>

                <div class="row ms-auto">
                    @foreach ($stok as $periode =>$data)
                    @php
                        $firstItem = $data->first(); // Ambil item pertama dalam grup
                        $stoktersisa = $data->sum('pupuk_tersisa'); // Hitung total stok tersisa
                    @endphp
                    <div class="col-lg-12 col-12 d-flex justify-content-end">
                        <div class="me-4">
                            <small>Periode</small>
                            <h6>{{ $firstItem->periode }}</h6>
                        </div>
                        @if ($stoktersisa == 0)
                        <div class="me-4"> 
                            <small>Pupuk Habis</small>
                            <h6>Jika belum mendapatkan pupuk anda, <br> mohon maaf penyaluran tidak dapatdilanjutkan</h6>
                        </div>
                        @else
                        @foreach ($data as $item)
                        <div class="me-4">
                            <small>{{ $item->pupuk->nama_pupuk }} (Kg)</small>
                            <h6>{{ number_format($item->pupuk_tersisa, 0, ',', '.') }}</h6>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>

@if ($penyaluran->isEmpty())
<div class="col-lg-12 col-12">
    <div class="custom-block custom-block-balance-red">
        <small>Penyaluran </small>

        <h2 class="mt-2 mb-3">Belum Tersedia</h2>

        
    </div>
</div>
    
@else

@foreach ($penyaluran as $index => $item)
    @php
        $gradientClass = $index % 2 == 0 ? 'custom-block-balance-red' : 'custom-block-balance-orange';
    @endphp
    <div class="row my-4">
        <div class="col-lg-4 col-12">
            <div class="custom-block {{ $gradientClass }}">
                <small>Komoditi</small>

                <h5 class="mt-2 mb-3">{{ $item->komoditi }}</h5>

                <div class="d-flex">
                    <div>
                        <small>Periode</small>
                        <p>{{ $item->periode }}</p>
                    </div>
                    <div class="ms-auto">
                        <small>tgl penyaluran</small>
                        <p class="text-end">{{ $item->tgl_penyaluran ? \Carbon\Carbon::parse($item->tgl_penyaluran)->format('d/m/Y') : '-' }}</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-8 col-12">
            <div class="custom-block custom-block-exchange">

                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <p >
                                @switch((int) $item->status_pembayaran)
                                            @case(0)
                                                <span class="badge text-bg-dark">Belum dibayar</span>
                                                @break
                                            @case(1)
                                                <span class="badge text-bg-success">Berhasil dibayar</span>
                                                @break
                                            @default
                                                <span class="badge text-bg-danger">Gagal dibayar</span>
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <div class="ms-auto ">
                        <small>Status Penyaluran</small>
                        <h6 class="text-end">
                            @switch((int) $item->status_penyaluran)
                                        @case(0)
                                            <span style="color: rgb(175, 175, 5)">Menunggu</span>
                                            @break
                                        @case(1)
                                            <span style="color: green">Berhasil</span>
                                            @break
                                        @default
                                           <span style="color: red">Gagal</span>
                            @endswitch

                        </h6>
                    </div>

                </div>

                @foreach ($rdkk as $r) 
                    @if ($r->komoditi==$item->komoditi && $r->periode==$item->periode)
                        <div class="d-flex align-items-center  pb-1">
                            <div class="d-flex align-items-center">

                                <div>
                                    <h6></h6>
                                    <small>{{ $r->pupuk->nama_pupuk }}</small>

                                </div>
                            </div>

                            <div class="ms-auto me-4">
                                <small>MT-1</small>
                                <h6>{{ $r->volume_pupuk_mt1 ?? "X" }}</h6>
                            </div>

                            <div class="me-4">
                                <small>MT-2</small>
                                <h6>{{ $r->volume_pupuk_mt2 ?? "X" }}</h6>
                            </div>

                            <div>
                                <small>MT-3</small>
                                <h6>{{ $r->volume_pupuk_mt3 ?? "X" }}</h6>
                            </div>


                        </div>                    
                    @endif               
                @endforeach
                
                <div class="d-flex align-items-center border-top pt-3 ">
                    <div class="d-flex align-items-center">

                        <div>
                            <h6></h6>
                            <small>Harga Total</small>

                        </div>
                    </div>

                    <div class="ms-auto me-3 ">
                        <h6 class="price">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</h6>
                    </div>

                    <div >
                        <form action="{{ route('penyaluran.detail') }}" method="GET">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="submit" class="btn custom-btn1">
                                Cek Detail
                                <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                            </button>
                        </form>                        
                    </div>


                </div>

            </div>
        </div>
    </div>
@endforeach

@endif
@endsection
@push('script')
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/custom.js"></script>

@endpush