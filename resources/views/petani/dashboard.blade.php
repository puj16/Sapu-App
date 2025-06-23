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
<div class="title-group mb-3">
    <h1 class="h2 mb-0">Overview</h1>

    <small class="text-muted">Hello {{ Auth::guard('petani')->user()->nama }}, welcome back!</small>
</div>

<div class="row my-4">
    <div class="col-lg-6 col-12">

        <div class="custom-block bg-white">
            <h5 class="mb-4">Data Pengajuan</h5>

            <div id="pie-chart"></div>
        </div>


        <div class="custom-block custom-block-bottom d-flex flex-wrap">
            <div class="custom-block-bottom-item">
                <a href="#" class="d-flex flex-column">
                    <i class="custom-block-icon">{{ $menunggu }}</i>

                    <small>Menunggu</small>
                </a>
            </div>

            <div class="custom-block-bottom-item">
                <a href="#" class="d-flex flex-column">
                    <i class="custom-block-icon">{{ $berhasil }}</i>

                    <small>Berhasil</small>
                </a>
            </div>

            <div class="custom-block-bottom-item">
                <a href="#" class="d-flex flex-column">
                    <i class="custom-block-icon">{{ $gagal }}</i>

                    <small>Gagal</small>
                </a>
            </div>

        </div>
    </div>
    <div class="col-lg-6 col-12">
        <div class="custom-block custom-block-transations">
            <h5 class="mb-4">Data Lahan Terbaru</h5>
            @foreach ($lahan as $item)
            <div class="d-flex flex-wrap align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="position-relative">
                    <img src="{{ asset('storage/assets/doc/sppt/' . $item->Foto_SPPT) }}" class="profile-image img-fluid"  alt="">
                    <a href="{{ asset('storage/assets/doc/sppt/' . $item->Foto_SPPT) }}" class="bi-eye custom-block-edit-icon position-absolute bottom-0 end-0" title="Lihat gambar"></a>
                </div>
                    <div>
                        <p>
                            <a href="transation-detail.html">{{ $item->NOP }}</a>
                        </p>

                        <small class="text-muted">{{ $item->status }}</small>
                    </div>
                </div>

                <div class="ms-auto">
                    <small>Luas</small>
                    <strong class="d-block text-danger"> {{ $item->Luas }}</strong>
                </div>
            </div>
            @endforeach

            <div class="border-top pt-4 mt-4 text-center">
                <a href="{{ url('/lahan') }}" class="btn custom-btn" href="wallet.html">
                    Lihat Semua Data Lahan
                    <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    
    <div class="title-group mb-3">
        <h1 class="h2 mb-0">RDKK</h1>

        <small class="text-muted">Berikut data RDKK</small>
    </div>

    <div class="col-lg-12 col-12">
        <div class="custom-block bg-white p-3">
            <div class="row align-items-center">
                @if ($rdkk->isEmpty())
                    <div class="col-lg-6 col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <p class="mb-4 text-black">Data tidak tersedia</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-end">
                        <div class="position-absolute top-0 end-0 p-3">
                            <form method="GET" action="{{ route('dashboard') }}" class="row g-2 d-flex justify-content-end">
                                <!-- Dropdown Tahun -->
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <!-- Dropdown Komoditi -->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <select name="komoditi" id="komoditi" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($komoditirdkk->pluck('komoditi')->unique()->sortDesc() as $k)
                                            <option value="{{ $k }}" {{ $komoditi == $k ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <!-- Dropdown Periode -->
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <select name="periode" id="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach($perioderdkk->pluck('periode')->unique()->sortDesc() as $p)
                                            <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
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
                    <p class="d-flex flex-wrap mt-3 mb-4">
                        <strong class="me-2">Komoditi:</strong>
                        <span>{{ $komoditi }}</span>
                    </p>
                </div>
                <div class="col-lg-6 col-12 d-flex justify-content-end">
                    <div class="position-absolute top-0 end-0 p-3">
                        <form method="GET" action="{{ route('dashboard') }}" class="row g-2 d-flex justify-content-end">
                            <!-- Dropdown Tahun -->
                            <div class="col-lg-5 col-md-6 col-sm-12">
                                <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Dropdown Komoditi -->
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <select name="komoditi" id="komoditi" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach($komoditirdkk->pluck('komoditi')->unique()->sortDesc() as $k)
                                        <option value="{{ $k }}" {{ $komoditi == $k ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Dropdown Periode -->
                            <div class="col-lg-3 col-md-6 col-sm-12">
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
                    <div class="table-responsive">
                        <table class="account-table table custom-table" id="scheduleTable1">
                    
                            <thead style="border: 1px solid black;text-align: center; ">
                                <tr>
                                    <th scope="col" rowspan="3" style="border: 1px solid black;">NO</th>
                                    <th scope="col" rowspan="3" style="border: 1px solid black;">NIK</th>
                                    <th scope="col" rowspan="3" style="border: 1px solid black;">Nama</th>
                                    <th scope="col" rowspan="3" style="border: 1px solid black;">Rencana Tanam (Ha)</th>
                                    <th scope="col" colspan="{{ $uniqueNamaPupuk->count() * 4 }}" style="border: 1px solid black;">Amount</th>
                                </tr>
                                <tr>
                                    @foreach ($uniqueNamaPupuk as $namaPupuk)
                                    <th scope="col" colspan="4" style="border: 1px solid black;">{{ $namaPupuk }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($uniqueNamaPupuk as $mtPupuk)
                                    <th scope="col" style="border: 1px solid black;">MT-1</th>
                                    <th scope="col" style="border: 1px solid black;">MT-2</th>
                                    <th scope="col" style="border: 1px solid black;">MT-3</th>
                                    <th scope="col" style="border: 1px solid black;">JML</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rdkk as $nik => $data)
                                    @php
                                        $firstItem = $data->first(); // Ambil item pertama dalam grup
                                    @endphp
                                    <tr>
                                        <td style="border: 1px solid black;">{{ $loop->iteration }}</td>
                                        <td style="border: 1px solid black;">
                                            {{ $firstItem->nik }}
                                        </td>
                                        <td style="border: 1px solid black;">
                                            {{ $firstItem->petani->nama ?? 'Petani tidak ditemukan' }}
                                        </td>
                                        <td style="border: 1px solid black;">
                                            {{ $firstItem->pengajuan->luasan ?? 'Pengajuan tidak ditemukan' }}
                                        </td>
                            
                                        @foreach ($uniqueNamaPupuk as $pupukNama)
                                            @php
                                                $rdkkItem = $data->where('pupuk.nama_pupuk', $pupukNama)->first();
                                            @endphp
                                            <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt1 ?? 0 }}</td>
                                            <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt2 ?? 0 }}</td>
                                            <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt3 ?? 0 }}</td>
                                            <td style="border: 1px solid black;">{{ 
                                                ($rdkkItem->volume_pupuk_mt1 ?? 0) + 
                                                ($rdkkItem->volume_pupuk_mt2 ?? 0) + 
                                                ($rdkkItem->volume_pupuk_mt3 ?? 0) 
                                            }}</td>
                                        @endforeach
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
@endsection

@push('scripts')
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/custom.js"></script>

<script type="text/javascript">
    // Ambil data dari PHP
    var menunggu = {{ $menunggu }};
    var berhasil = {{ $berhasil }};
    var gagal = {{ $gagal }};
    var total = menunggu + berhasil + gagal;

    // Hitung persentase
    var menungguPercent = total > 0 ? (menunggu / total) * 100 : 0;
    var berhasilPercent = total > 0 ? (berhasil / total) * 100 : 0;
    var gagalPercent = total > 0 ? (gagal / total) * 100 : 0;

    var options = {
        series: [menungguPercent, berhasilPercent, gagalPercent],
        chart: {
            width: 337,
            type: 'pie',
        },
        colors: ['#feb019', '#00e396', '#e63946'],
        labels: ['Menunggu', 'Berhasil', 'Gagal'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
    chart.render();
</script>

<script>
    $(document).ready(function () {
        var table = $('#scheduleTable1').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "lengthMenu": [10,20,30,50,60],
            "language": {
                "search": "",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            },
            "autoWidth": false, // Mencegah DataTables mengubah lebar kolom
            "scrollCollapse": false, // Mencegah DataTables mengubah tampilan
            "columnDefs": [
                { "className": "text-center", "targets": "_all" } // Paksa teks di tengah
            ]
        });

    // Ubah placeholder search
        $('#scheduleTable1_filter input').attr('placeholder', 'Search');

        $('#scheduleTable1_filter').append($('#customButtonWrapper'));

    
        // Nonaktifkan select pengajuan di awal
        });
</script>

@endpush