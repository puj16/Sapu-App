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
    <a class="text-muted me-2" style="color: rgb(95, 95, 240) !important; text-decoration: underline;" href="{{ route('penyaluran.index') }}">Penyaluran </a>
    <a class="text-muted me-2"> >> </a>
    <a class="text-muted" href="{{ route('detailpenyaluran') }}">Konfirmasi Penyaluran</a>
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
                        <div class="d-flex align-items-center gap-2 p-3">
                            <form method="GET" action="{{ route('detailpenyaluran') }}" class="d-inline-block">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="periode" value="{{ $periode }}">
                                <input type="hidden" name="komoditi" value="{{ $komoditi }}">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="" disabled {{ request('status') === null ? 'selected' : '' }}>Status Penyaluran</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Gagal</option>
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </form>
                            <a class="btn custom-btn1 ms-2" href="{{ route('penyaluran.index') }}">
                                Back
                                <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                            </a>                
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
                            <span>{{ $komoditi}}</span>
                        </p>
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-end">
                        <div class="position-absolute top-0 end-0 p-3">
                            @if (auth()->user()->role == 0)
                            <a class="btn custom-btn1" href="{{ route('penyaluran.index') }}">
                            @elseif (auth()->user()->role == 1)
                            <a class="btn custom-btn1" href="{{ route('index.penyaluran') }}">
                            @endif
                                <i class="bi-arrow-up-left-circle-fill me-1"></i>
                                Kembali
                            </a>                
                        </div>                        
                    </div>

                    <div class="col-lg-12 col-12">
                        <div id="customButtonWrapper" class="d-inline-block ms-2">
                            @if (auth()->user()->role == 0)
                            <form action="{{ route('penyaluran.reports') }}" method="get">
                                @csrf
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="periode" value="{{ $periode }}">
                                <input type="hidden" name="komoditi" value="{{ $komoditi }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                            </form>
                            @elseif(auth()->user()->role == 1)
                            <form action="{{ route('reports.penyaluran') }}" method="get">
                                @csrf
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="periode" value="{{ $periode }}">
                                <input type="hidden" name="komoditi" value="{{ $komoditi }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                            </form>
                            @endif
                        </div>
                        <div id="statuspenyaluran" class="d-inline-block ms-2">      
                            <form method="GET" action="{{ route('detailpenyaluran') }}" class="row g-2 w-100">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="periode" value="{{ $periode }}">
                                <input type="hidden" name="komoditi" value="{{ $komoditi }}">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="" disabled {{ request('status') === null ? 'selected' : '' }}>Status Penyaluran</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Gagal</option>
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="account-table table" id="scheduleTable">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">NIK</th>
                                        <th scope="col">Pupuk</th>
                                        <th scope="col">Total Bayar (Rp)</th>
                                        <th scope="col">Tgl Bayar</th>
                                        <th scope="col">Tgl Penyaluran</th>
                                        {{-- <th scope="col">Metode Bayar</th> --}}
                                        <th scope="col">Status Pembayaran</th>
                                        <th scope="col">Status Penyaluran</th>
                                        @if(auth()->user()->role == 0)
                                        <th scope="col">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Penyaluran as $item)
                                    <tr>
                                        <td scope="row">{{ $loop->iteration }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>
                                            <li style="display: flex; justify-content: space-between; padding: 2px 0;">
                                                <small style="min-width: 100px;">Pupuk</small>
                                                <small style="text-align: right;">Volume</small>
                                            </li>                                                        
                                            @php
                                                $ketersediaan = [];
                                                $dataBaru = []; // Inisialisasi variabel untuk menyimpan data baru
                                            @endphp
                                            @foreach ($rdkk as $r)
                                                @if ($r->nik==$item->nik)
                                                    <li style="display: flex; justify-content: space-between; padding: 2px 0;">
                                                        <small style="min-width: 100px;">{{ $r->pupuk->nama_pupuk }}</small>
                                                        <small style="text-align: right;">{{ ($r->volume_pupuk_mt1+$r->volume_pupuk_mt2+$r->volume_pupuk_mt3) }}</small>
                                                    </li>  
                                                    @php
                                                        $total_volume = $r->volume_pupuk_mt1 + $r->volume_pupuk_mt2 + $r->volume_pupuk_mt3;  
                                                        $dataBaru[] = [
                                                        'id' => $r->id_pupuk,
                                                        'voluPupuk' => $total_volume,
                                                    ];
                                                    @endphp                                                   
                                                @endif
                                                @php
                                                    $stoktersedia = $stok->where('id_pupuk', $r->id_pupuk);
                                                    if ($stoktersedia->isNotEmpty()) {
                                                        $ketersediaan[] = 'siap';
                                                    } else {
                                                        $ketersediaan[] = 'belum'; // Atau nilai default lainnya
                                                    };
                                                @endphp
                                            @endforeach

                                            @if (auth()->user()->role == 0) 
                                            <form action="{{ route('rdkk.index') }}" method="GET">
                                            @elseif (auth()->user()->role == 1)
                                            <form action="{{ route('index.rdkk') }}" method="GET">
                                            @endif
                                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <input type="hidden" name="komoditi" value="{{ $komoditi }}">
                                                <button type="submit" class="custom-btn-black">
                                                    Cek RDKK <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                                                </button>
                                            </form>

                                        </td>
                                        <td>{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                        <td>{{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $item->tgl_penyaluran ? \Carbon\Carbon::parse($item->tgl_penyaluran)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @switch((int) $item->status_pembayaran)
                                                        @case(0)
                                                            <span class="badge text-bg-dark">Menunggu</span> <br>
                                                            @break
                                                        @case(1)
                                                            <span class="badge text-bg-success">Berhasil</span> <br>
                                                            <span>Metode Bayar : {{ $item->metode_bayar ?? '-' }}</span>
                                                            @break
                                                        @default
                                                            <span class="badge text-bg-danger">Gagal</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch((int) $item->status_penyaluran)
                                                        @case(0)
                                                            <span class="badge text-bg-dark">Menunggu</span>
                                                            @break
                                                        @case(1)
                                                            <span class="badge text-bg-success">Berhasil</span>
                                                            @break
                                                        @case(2)
                                                            <span class="badge text-bg-danger">Gagal</span>
                                                            @break
                                                        @default
                                                            <span class="badge text-bg-edit">Disalurkan Sebagian</span>
                                                        @endswitch
                                            <button type="button" class="badge text-bg-black mt-1" data-bs-toggle="modal" data-bs-target="#infoModal{{ $item->id }}" title="Info Pupuk">
                                                Info Pupuk <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                                            </button>

                                        </td>
                                        @if(auth()->user()->role == 0)
                                        <td>                    
                                            @php
                                                $belumSiap = in_array('belum', $ketersediaan);
                                            @endphp
                                            @switch((int) $item->status_penyaluran)
                                                        @case(0)
                                                        @if ($belumSiap)
                                                            <button class="custom-btn-black">
                                                                Konfirmasi 
                                                            </button>                                                        
                                                        @else
                                                            <button class="custom-btn-black" data-bs-toggle="modal" data-bs-target="#konfirmasiModal{{ $item->id }}">
                                                                Konfirmasi <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                                                            </button>                                                        

                                                            {{-- <button class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#konfirmasiModal{{ $item->id }}">Konfirmasi </button> --}}
                                                        @endif
                                                        @break
                                                        @case(1)
                                                        <span  title="Penyaluran Berhasil">
                                                            <i class="bi bi-check2-all icon"></i>                                                        
                                                        </span>
                                                            @break
                                                        @case(2)
                                                        <span  title="Penyaluran Gagal">
                                                            <i class="bi bi-x-circle icon"></i>                                          
                                                        </span>
                                                            @break
                                                        @default
                                                        <span  title="Disalurkan Sebagian">
                                                            <i class="bi bi-check2 icon"></i>                                                        
                                                        </span>
                                            @endswitch
                                        </td>
                                        @endif
                                    </tr>
                                {{-- Modal --}}
                                    <div class="modal fade" id="berhasilModal{{$item->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-center">
                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <i class="bi bi-clipboard-check icon-modal"></i>
                                                    <h5 class="mt-3">Apakah benar tersalurkan?</h5>
                                                    <p>Pastikan pupuk yang disalurkan sudah dibayar, dan disalurkan sesuai volumenya</p>
                                                </div>
                                                <div class="modal-footer border-0 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#noteModal{{$item->id}}">Tidak</button>
                                                    <form action="{{ route('penyaluran.berhasil') }}" method="get" class="d-inline-block">
                                                        <div class="form-group mb-3">
                                                            <input type="hidden" name=id value="{{$item->id}}">                                                       </div> 
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Ya</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Gagal Modal --}}
                                    <div class="modal fade" id="gagalModal{{$item->id}}" tabindex="-1" aria-labelledby="noteModalLabel{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-center">
                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('penyaluran.gagal') }}" method="get" class="d-inline-block">
                                                        @csrf
                                                    <i class="bi bi-clipboard-check icon-modal"></i>
                                                    <h5 class="mt-3">Benarkah Pupuk Gagal Disalurkan?</h5>
                                                    <p class="mb-1">Berikan catatan, berupa informasi pupuk saat ini!</p>
                                                    <div class="form-group mb-3">
                                                        <input type="text" class="form-control" name="info" id="info" placeholder="Informasi Pupuk" required>
                                                    </div>  
                                                    <div class="form-group mb-3">
                                                        <input type="hidden" name=id value="{{$item->id}}">                                                       
                                                        <input type="hidden" name=status_penyaluran value="3">                                                       
                                                    </div> 
                                                </div>
                                                <div class="modal-footer border-0 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Konfirmasi Modal --}}
                                    <div class="modal fade" id="konfirmasiModal{{$item->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
                                            <div class="modal-content ">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Penyaluran</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input me-2" type="radio" name="konfirmasi_{{$item->id}}" id="confirmRadio1{{$item->id}}">
                                                        <label class="form-check-label" for="confirmRadio1{{$item->id}}">
                                                            Pupuk disalurkan seluruhnya sesuai dengan volume yang tertera pada RDKK. 
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input me-2" type="radio" name="konfirmasi_{{$item->id}}" id="confirmRadio2{{$item->id}}">
                                                        <label class="form-check-label" for="confirmRadio2{{$item->id}}">
                                                            Pupuk disalurkan sebagian, tidak sesuai dengan volume yang tertera pada RDKK.
                                                        </label>
                                                    </div>  
                                                    <div class="form-check">
                                                        <input class="form-check-input me-2" type="radio" name="konfirmasi_{{$item->id}}" id="confirmRadio3{{$item->id}}">
                                                        <label class="form-check-label" for="confirmRadio3{{$item->id}}">
                                                            Pupuk tidak disalurkan, karena pupuk tidak tersedia atau habis.
                                                        </label>
                                                    </div>                                                  
                                                </div>
                                                <div class="modal-footer border-0 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#noteModal{{$item->id}}">Tidak</button>
                                                    <form action="{{ route('penyaluran.konfirmasi') }}" method="get" class="d-inline-block">
                                                        <div class="form-group mb-3">
                                                            <input type="hidden" name=id value="{{$item->id}}">
                                                            <input type="hidden" name=status_penyaluran id="status" value="">
                                                            @foreach ($dataBaru as $index => $data)
                                                                <input type="hidden" name="dataBaru[{{ $index }}][id]" value="{{ $data['id'] }}">
                                                                <input type="hidden" name="dataBaru[{{ $index }}][voluPupuk]" value="{{ $data['voluPupuk'] }}">
                                                            @endforeach                                                        </div> 
                                                        @csrf
                                                        <button type="button" class="btn btn-danger" onclick="handleKonfirmasi({{$item->id}})">Ya</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Sebagian Modal --}}
                                    <div class="modal fade" id="sebagianModal{{$item->id}}" tabindex="-1" aria-labelledby="noteModalLabel{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-center">
                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('penyaluran.konfirmasi') }}" method="get" class="d-inline-block">
                                                        @csrf
                                                    <i class="bi bi-clipboard-check icon-modal"></i>
                                                    <p class="mb-1">Isikan jumlah pupuk yang diterima petani</p>
                                                    
                                            @foreach ($rdkk as $r)
                                                @if ($r->nik==$item->nik)

                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group mb-3">
                                                                <input type="text" class="form-control"  value="{{ $r->pupuk->nama_pupuk }}" required>
                                                                <input type="hidden" name="pupuk[]" value="{{ $r->id_pupuk }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group mb-3">
                                                                @php
                                                                    $totalVolume = $r->volume_pupuk_mt1 + $r->volume_pupuk_mt2 + $r->volume_pupuk_mt3;
                                                                    $stokTersisa = $stok->where('id_pupuk', $r->id_pupuk)->first()->pupuk_tersisa ?? 0;
                                                                @endphp
                                                                @if ($totalVolume > $stokTersisa)
                                                                    <input type="text" class="form-control" name="vol[]" value="{{ ($stokTersisa) }}" required readonly>
                                                                @else
                                                                    <input type="text" class="form-control" name="vol[]" value="{{ ($totalVolume) }}" required readonly>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <div class="form-group mb-3">
                                                        <input type="hidden" name=id value="{{$item->id}}">
                                                        <input type="hidden" name=status_penyaluran value="3">                                                                                                              
                                                    </div>
                                                @endif
                                            @endforeach 
                                                </div>
                                                <div class="modal-footer border-0 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Info Modal --}}
                                    <div class="modal fade" id="infoModal{{$item->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-center">
                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @switch($item->status_penyaluran)
                                                        @case(0)
                                                        <i class="bi bi-clock-history icon-modal"></i>
                                                        <h5 class="mt-3">Pupuk belum disalurkan!</h5>   
                                                            @break
                                                        @case(1)
                                                        <i class="bi bi-clipboard-check icon-modal"></i>
                                                        <h5 class="mt-3">Pupuk berhasil disalurkan!</h5>
                                                            @break
                                                        @case(2)
                                                        <i class="bi bi-clipboard-x icon-modal"></i>
                                                        <h5 class="mt-3">Pupuk gagal disalurkan!</h5>
                                                        <p>Informasi menganai pupuk : {{ $item->info }}</p>
                                                            @break
                                                        @default
                                                        <h5 class="mt-3">Rincian Sebagian Pupuk yang disalurkan!</h5>
                                                        @php
                                                            $details = $detailPenyaluranSebagian->where('id_penyaluran', $item->id);
                                                        @endphp
                                                        @foreach ($details as $detail) 
                                                            <li style="display: flex; justify-content: space-between; padding: 2px 0;">
                                                                <small style="min-width: 100px;">{{ $detail->pupuk->nama_pupuk }}</small>
                                                                <small style="text-align: right;">{{ $detail->volume_pupuk }}</small>
                                                            </li>
                                                        @endforeach
                                                    @endswitch
                                                </div>
                                                <div class="modal-footer border-0 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
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
            "lengthMenu": [ 10, 25,30], // Menentukan jumlah data per halaman
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
                $('#scheduleTable_filter').append($('#statuspenyaluran'));

        $('#scheduleTable_filter').append($('#customButtonWrapper'));

    });
</script>

<script>
    function handleKonfirmasi(id) {
        const radio1 = document.getElementById(`confirmRadio1${id}`);
        const radio2 = document.getElementById(`confirmRadio2${id}`);
        const radio3 = document.getElementById(`confirmRadio3${id}`);
        const statusInput = document.querySelector(`#konfirmasiModal${id} input[name='status_penyaluran']`);
        const form = document.querySelector(`#konfirmasiModal${id} form`);

        if (radio1.checked) {
            // Pupuk disalurkan seluruhnya
            statusInput.value = 1;
            form.submit();
        } else if (radio2.checked) {
            // Pupuk disalurkan sebagian → buka modal
            const sebagianModal = new bootstrap.Modal(document.getElementById(`sebagianModal${id}`));
            sebagianModal.show();
            // Tutup modal konfirmasi utama
            const konfirmasiModal = bootstrap.Modal.getInstance(document.getElementById(`konfirmasiModal${id}`));
            konfirmasiModal.hide();
        } else if (radio3.checked) {
            // Pupuk tidak disalurkan
            statusInput.value = 2;
            form.submit();
        } else {
            alert('Silakan pilih salah satu opsi konfirmasi terlebih dahulu.');
        }
    }
</script>

@endpush