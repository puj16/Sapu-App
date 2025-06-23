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
    <h1 class="h2 mb-0">Data Pengajuan</h1>
</div>

<div class="row my-4">
    <div class="col-lg-12 col-12">
        <div class="custom-block bg-white p-3">
            <div class="row align-items-center">
                <!-- Judul Tahun -->
                <div class="col-lg-6 col-md-4 col-sm-12 text-center text-md-start mb-2 mb-md-0 d-flex">
                    <h5 class="mb-0">Tahun {{ $tahun }}</h5>
                    
                    <div id="customButtonWrapper" class="d-inline-block ms-2">
                        @if (auth()->user()->role == 0)
                        <form action="{{ route('pengajuan.report') }}" method="get">
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="status_validasi" value="{{ $status_validasi }}">
                            <button type="submit"  id="btn-tambah" style="border: none; border-radius:5px; background:#0d6efd; color:white;"><i class="bi bi-filetype-pdf icon"></i></button>
                        </form>
                        @elseif (auth()->user()->role == 1)
                        <form action="{{ route('report.pengajuan') }}" method="get">
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="status_validasi" value="{{ $status_validasi }}">
                            <button type="submit"  id="btn-tambah" style="border: none; border-radius:5px; background:#0d6efd; color:white;"><i class="bi bi-filetype-pdf icon"></i></button>
                        </form>
                        @endif
                    </div>
    
                </div>
        
                <!-- Form Pilihan -->
                <div class="col-lg-6 col-md-8 col-sm-12 d-flex justify-content-end">
                    @if(auth()->user()->role == 0)
                    <form method="GET" action="{{ route('pengajuan.show') }}" class="row g-2 d-flex justify-content-end">
                    @elseif(auth()->user()->role == 1)
                    <form method="GET" action="{{ route('show.pengajuan') }}" class="row g-2 d-flex justify-content-end">
                    @endif
                        <!-- Dropdown Tahun -->
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Tahun --</option>
                                @foreach($tahunajuan->pluck('tahun')->unique()->sortDesc() as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <!-- Dropdown Status Validasi -->
                        <div class="col-lg-7 col-md-6 col-sm-12">
                            <select name="status_validasi" id="status_validasi" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ $status_validasi == 'all' ? 'selected' : '' }}>Seluruh Data</option> 
                                <option value="0" {{ $status_validasi == 0 ? 'selected' : '' }}>Menunggu Validasi</option>
                                <option value="1" {{ $status_validasi == 1 ? 'selected' : '' }}>Validasi Berhasil</option>
                                <option value="2" {{ $status_validasi == 2 ? 'selected' : '' }}>Validasi Gagal</option>
                            </select>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
        
        
        
        

        @if ($pengajuan->isEmpty())
            <div class="custom-block bg-white mt-3">
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <p class="mb-0 text-black">Data tidak tersedia</p>
                </div>
            </div>
        @else
        
            @foreach ($pengajuan as $komoditi => $data) 
            <div class="custom-block bg-white">
                <p class="d-flex flex-wrap mt-3 mb-4">
                    <strong class="me-2">Komoditi:</strong>
                    <span>{{ $komoditi }}</span>
                </p>

               
                <div class="table-responsive">
                    <table class="account-table table" id="scheduleTable-{{ Str::slug($komoditi) }}">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">ID</th>
                                <th scope="col">NOP</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Luasan(Ha)</th>
                                <th scope="col">Status</th>
                                <th scope="col">Berkas Terkait</th>
                                @if(auth()->user()->role == 0)
                                <th scope="col">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $pengajuan)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td scope="row">{{ $pengajuan->id_pengajuan }}</td>
                                    <td scope="row">{{ $pengajuan->NOP }}</td>
                                    <td scope="row">{{ $pengajuan->nik}}</td>
                                    <td scope="row">{{ $pengajuan->luasan }}</td>
                                    <td scope="row">
                                        @switch((int) $pengajuan->status_validasi)
                                            @case(0)
                                                <span class="badge text-bg-dark">Menunggu Validasi</span>
                                                @break
                                            @case(1)
                                                <span class="badge text-bg-success">Validasi Berhasil</span>
                                                @break
                                            @default
                                                <span class="badge text-bg-danger">Validasi Gagal</span>
                                        @endswitch
                                    </td>                                                                      
                                    <td scope="row">
                                    @if(auth()->user()->role == 0)
                                        <form action="{{route('pengajuan.cek-berkas', $pengajuan->id_pengajuan)}}" method="post" style="border: none">
                                            @csrf
                                            <button type="submit" class="badge text-bg-black" style="border: none">Cek Berkas <i class="bi-arrow-up-right-circle-fill ms-2"></i></button>
                                        </form>
                                    @elseif (auth()->user()->role == 1)
                                        <form action="{{route('cek-berkas.pengajuan', $pengajuan->id_pengajuan)}}" method="post" style="border: none">
                                            @csrf
                                            <button type="submit" class="badge text-bg-black" style="border: none">Cek Berkas <i class="bi-arrow-up-right-circle-fill ms-2"></i></button>
                                        </form>
                                    @endif
                                    </td>
                                    @if(auth()->user()->role == 0)
                                    <td scope="row">
                                        <span class="badge text-bg-edit" data-bs-toggle="modal" data-bs-target="#deleteModal{{$pengajuan->id_pengajuan}}" title="Validasi"  @if($pengajuan->status_validasi != 0) style="pointer-events: none; opacity: 0.5;" @endif>
                                            <i class="bi-clipboard-check icon"></i>
                                        </span>
                                    </td>
                                    @endif
                                </tr>

                                @if(auth()->user()->role == 0)
                                <!-- Modal Validasi -->
                                <div class="modal fade" id="deleteModal{{$pengajuan->id_pengajuan}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$pengajuan->id_pengajuan}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-center">
                                            <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <i class="bi bi-clipboard-check icon-modal"></i>
                                                <h5 class="mt-3">Apakah data ini valid?</h5>
                                            </div>
                                            <div class="modal-footer border-0 d-flex justify-content-center">
                                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#noteModal{{$pengajuan->id_pengajuan}}">Tidak</button>
                                                <form action="{{ route('pengajuan.validasi', $pengajuan->id_pengajuan) }}" method="post" class="d-inline-block">
                                                    <div class="form-group mb-3">
                                                        <input type="hidden" class="form-control" name="status_validasi" id="status_validasi" value="1" required>
                                                    </div> 
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger">Ya</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal tidak valid -->
                                <div class="modal fade" id="noteModal{{$pengajuan->id_pengajuan}}" tabindex="-1" aria-labelledby="noteModalLabel{{$pengajuan->id_pengajuan}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-center">
                                            <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('pengajuan.validasi', $pengajuan->id_pengajuan) }}" method="post" class="d-inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                <i class="bi bi-clipboard-check icon-modal"></i>
                                                <p class="mb-1">Berikan catatan, berupa alasan tidak validnya data!</p>
                                                <div class="form-group mb-3">
                                                    <input type="text" class="form-control" name="catatan" id="catatan" placeholder="catatan" required>
                                                </div>  
                                                <div class="form-group mb-3">
                                                    <input type="hidden" class="form-control" name="status_validasi" id="status_validasi" value="2" required>
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
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        @endif
        </div>
    </div>




@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("table[id^='scheduleTable']").forEach(table => {
            var dataTable = new DataTable(table, {
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
            let searchInput = table.closest('.dataTables_wrapper').querySelector('.dataTables_filter input');
            if (searchInput) {
                searchInput.setAttribute('placeholder', 'Search');
            }

        });
    });
</script>

@endpush


