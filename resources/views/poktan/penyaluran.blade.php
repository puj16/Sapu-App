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
                @if ($Penyaluran->isEmpty())
                    <div class="col-lg-6 col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <p class="mb-4 text-black">Data tidak tersedia</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-end">
                        <div class="position-absolute top-0 end-0 p-3">
                            @if (auth()->user()->role == 0)
                            <form method="GET" action="{{ route('penyaluran.index') }}" class="row g-2 d-flex justify-content-end">
                                <!-- Dropdown Tahun -->
                                <div class="col-lg-8 col-md-6 col-sm-12">
                                    <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <!-- Dropdown Periode -->
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($perioderdkk->pluck('periode')->unique()->sortDesc() as $p)
                                            <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            @elseif (auth()->user()->role == 1)
                            <form method="GET" action="{{ route('index.penyaluran') }}" class="row g-2 d-flex justify-content-end">
                                <!-- Dropdown Tahun -->
                                <div class="col-lg-8 col-md-6 col-sm-12">
                                    <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <!-- Dropdown Periode -->
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($perioderdkk->pluck('periode')->unique()->sortDesc() as $p)
                                            <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            @endif
                        </div>
                        
                    </div>
                @else
                    <div class="col-lg-6 col-12">
                        <p class="d-flex flex-wrap mt-3 mb-4">
                            <strong class="me-2">Tahun:</strong>
                            <span>{{ $tahun}}</span>
                        </p>
                        <p class="d-flex flex-wrap mt-3 mb-4">
                            <strong class="me-2">Periode:</strong>
                            <span>{{ $periode}}</span>
                        </p>
                        @php
                            $firstItem = $Penyaluran->first();
                        @endphp 
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-end">
                        <div class="position-absolute top-0 end-0 p-3">
                            @if(auth()->user()->role == 0)
                            <form method="GET" action="{{ route('penyaluran.index') }}" class="row g-2 d-flex justify-content-end">
                            @elseif(auth()->user()->role == 1)
                            <form method="GET" action="{{ route('index.penyaluran') }}" class="row g-2 d-flex justify-content-end">
                            @endif
                                <!-- Dropdown Tahun -->
                                <div class="col-lg-8 col-md-6 col-sm-12">
                                    <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <!-- Dropdown Komoditi -->
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($perioderdkk->pluck('periode')->unique()->sortDesc() as $p)
                                            <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                            </form>
                        </div>
                        
                    </div>
                    
                    <div class="col-lg-12 col-12">
                        <div id="customButtonWrapper" class="d-inline-block ms-2">
                            @if(auth()->user()->role == 0)
                            <form action="{{ route('penyaluran.report') }}" method="get">
                                @csrf
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="periode" value="{{ $periode }}">
                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                            </form>
                            @elseif(auth()->user()->role == 1)
                            <form action="{{ route('report.penyaluran') }}" method="get">
                                @csrf
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="periode" value="{{ $periode }}">
                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                            </form>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="account-table table" id="scheduleTable">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Komoditi</th>
                                        <th scope="col">Penerima Pupuk</th>
                                        <th scope="col" style="display: flex; justify-content: space-between;">Pupuk <span class="ms-4 me-0">Tersalurkan/Total (Kg)</span></th>
                                        <th scope="col">Terbayar / Total Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Penyaluran as $komoditi=>$data)
                                    @php
                                        $firstItem = $data->first(); // Ambil item pertama dalam grup
                                    @endphp
                                    <tr>
                                        <td scope="row">{{ $loop->iteration }}</td>
                                        <td>{{ $firstItem->komoditi }}</td>
                                        <td>{{ $statusPenyaluran[$komoditi] ?? '-' }}
                                            @if (auth()->user()->role == 0)
                                            <form action="{{ route('detailpenyaluran') }}" method="GET">
                                                <input type="hidden" name="tahun" value="{{ $firstItem->tahun }}">
                                                <input type="hidden" name="periode" value="{{ $firstItem->periode }}">
                                                <input type="hidden" name="komoditi" value="{{ $firstItem->komoditi }}">
                                                <button type="submit" class="custom-btn-black">
                                                    Konfirmasi <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                                                </button>
                                            </form>
                                            @elseif (auth()->user()->role == 1)
                                            <form action="{{ route('detail.penyaluran') }}" method="GET">
                                                <input type="hidden" name="tahun" value="{{ $firstItem->tahun }}">
                                                <input type="hidden" name="periode" value="{{ $firstItem->periode }}">
                                                <input type="hidden" name="komoditi" value="{{ $firstItem->komoditi }}">
                                                <button type="submit" class="custom-btn-black">
                                                    Konfirmasi <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                                                </button>
                                            </form>

                                            @endif
                                            </td>
                                        <td>
                                            <ul style="list-style: none; padding: 0; margin: 0;">
                                                @php
                                                    $pupukIds = $rdkkData->where('komoditi', $firstItem->komoditi)
                                                                        ->pluck('id_pupuk')
                                                                        ->unique();
                                                @endphp
                                        
                                                @foreach ($pupukIds as $item)
                                                @php
                                                    $pupuk = \App\Models\Pupuk::findOrFail($item);
                                                    $volume = $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt1') 
                                                                + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt2') 
                                                                + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt3');
                                                    $niktersalur = $data->where('status_penyaluran',1)->pluck('nik') ->unique();
                                                    $idtersalursebagian = $data->where('status_penyaluran',3)->pluck('id') ->unique();
                                                    $voltersalur =0;
                                                    $voltersalursebagian =0;
                                                    foreach ($niktersalur as $n) {
                                                        $volpernik= $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt1') 
                                                                + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt2') 
                                                                + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt3');
                                                                $voltersalur +=$volpernik;
                                                    }
                                                    foreach ($idtersalursebagian as $n) {
                                                        $volperid= $details->where('id_pupuk', $item)->where('id_penyaluran',$n)->first()->volume_pupuk;
                                                                $voltersalursebagian +=$volperid;}
                                                    $voltersalurkeseluruhan = $voltersalur + $voltersalursebagian;
                                                @endphp
                                                    <li style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; padding: 2px 0;">
                                                        <p style="margin: 0;">{{ $pupuk->nama_pupuk }}</p>
                                                        <p style="margin: 0;">{{ $voltersalurkeseluruhan }} / {{ $volume }} </p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td> 
                                            @php
                                                $totalterbayar = $data->where('status_pembayaran',1)->sum('total_bayar');
                                                $totalbayar = $data->sum('total_bayar');
                                            @endphp
                                            <b>Rp{{ number_format($totalterbayar, 0, ',', '.') }} / Rp{{ number_format($totalbayar, 0, ',', '.') }}</b>                                        </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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
                        <form method="GET" action="{{ route('penyaluran.index') }}">
                        @elseif (auth()->user()->role==1)
                        <form method="GET" action="{{ route('index.penyaluran') }}">
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
                        <form method="GET" action="{{ route('penyaluran.index') }}">
                        @elseif (auth()->user()->role==1)
                        <form method="GET" action="{{ route('index.penyaluran') }}">
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

{{-- @if(auth()->user()->role == 0)
        <div class="form-crud">
            <div class="add">
                <div class="modal fade" id="add" tabindex="-1" aria-labelledby="data-leModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg center">
                        <div class="modal-content ">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Penyaluran</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <form lang="id" action="{{ route('penyaluran.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group mb-3">
                                                    <label for="tahun">Tahun</label>
                                                    <select class="form-select form-select-sm" aria-label="Small select example" name="tahun">
                                                        <option value="{{ \Carbon\Carbon::now()->year }}">{{ \Carbon\Carbon::now()->year }}</option>
                                                        @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                                        <option  value="{{ $t}}" >{{ $t}}</option>
                                                        @endforeach                                
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="periode">Periode</label>
                                                    <select class="form-select form-select-sm" aria-label="Small select example" name="periode">
                                                        <option value="">-- Pilih periode periode rdkk --</option>
                                                        @foreach($perioderdkk->pluck('periode')->unique()->sortDesc() as $t)
                                                        <option value="{{ $t}}" >{{ $t}}</option>
                                                        @endforeach                                
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="id">Tanggal Penyaluran<span class="text-danger">*</span></label>
                                                    <input class="form-control "  type="date"  name="tgl_penyaluran" placeholder="m" required>
                                                </div>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endif --}}
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