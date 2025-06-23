@extends('layouts.masterpetani')

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
    <div class="profile-rounded">
        <a href="#" class="add-data" data-bs-toggle="modal" data-bs-target="#add">
            <i class="profile-rounded-icon bi-plus"></i>
        </a>
    </div>
</div>


<div class="row my-4">
    <div class="col-lg-12 col-12">
        <div class="custom-block bg-white p-3">
            <div class="row align-items-center">
                <!-- Judul Tahun -->
                <div class="col-lg-3 col-md-4 col-sm-12 text-center text-md-start mb-2 mb-md-0">
                    <h5 class="mb-0">Tahun {{ $tahun }}</h5>
                </div>
        
                <!-- Form Pilih Tahun -->
                <div class="col-lg-9 col-md-12 col-sm-12 d-flex justify-content-end">
                    <form method="GET" action="{{ route('pengajuan.index') }}" class="row g-2 align-items-center d-flex justify-content-end">
                        <!-- Dropdown Tahun -->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($tahunajuan->pluck('tahun')->unique()->sortDesc() as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
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
                                <th scope="col">ID Ajuan</th>
                                <th scope="col">NOP</th>
                                <th scope="col">Luasan (Ha)</th>
                                <th scope="col">Status</th>
                                <th scope="col">Berkas Terkait</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $pengajuan)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td scope="row">{{ $pengajuan->id_pengajuan }}</td>
                                    <td scope="row">{{ $pengajuan->NOP }}</td>
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
                                        <form action="{{route('pengajuan.berkas', $pengajuan->id_pengajuan)}}" method="post" style="border: none">
                                            @csrf
                                            <button type="submit" class="badge text-bg-black" style="border: none">Cek Berkas <i class="bi-arrow-up-right-circle-fill ms-2"></i></button>
                                        </form>
                                    </td>
                                    <td scope="row">
                                        @if ($pengajuan->status_validasi == 0)
                                            <span class="badge text-bg-edit" data-bs-toggle="modal" data-bs-target="#editModal{{$pengajuan->id_pengajuan}}" title="Edit">
                                                <i class="bi-pencil-square icon"></i>
                                            </span>
                                            <span class="badge text-bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$pengajuan->id_pengajuan}}" title="Hapus">
                                                <i class="bi bi-trash3 icon"></i>
                                            </span>
                                        @else
                                            <span class="badge text-bg-edit" title="Edit"
                                                style="background:#81b3db; color:#a0a0a0; cursor:not-allowed; pointer-events:none; opacity:0.6;">
                                                <i class="bi-pencil-square icon"></i>
                                            </span>
                                            <span class="badge text-bg-danger" title="Hapus"
                                                style="background:#da8a8a; color:#a0a0a0; cursor:not-allowed; pointer-events:none; opacity:0.6;">
                                                <i class="bi bi-trash3 icon"></i>
                                            </span>
                                        @endif                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="edit">
                                    <div class="modal fade" id="editModal{{$pengajuan->id_pengajuan}}" tabindex="-1" aria-labelledby="editModalLabel{{$pengajuan->id_pengajuan}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Pengajuan</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('pengajuan.update', $pengajuan->id_pengajuan)}}" method="post" enctype="multipart/form-data">
                                                        @csrf  
                                                        @method('PUT')                                              
                                                        <div class="row">
                                                            <div class="col-md-6 col-12">
                                                                <div class="form-group mb-3">
                                                                    <label for="id_pengajuan">ID Pengajuan</label>
                                                                    <input type="text" class="form-control" name="id_pengajuan" id="id_pengajuan" value="{{ $pengajuan->id_pengajuan }}" readonly>
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label for="nop">NOP</label>
                                                                    <select class="form-select" name="nop">
                                                                        <option>-- Pilih NOP lahan --</option>
                                                                        @foreach($lahan as $itemLahan)
                                                                        <option value="{{ $itemLahan->NOP }}" {{ $itemLahan->NOP == $pengajuan->NOP ? 'selected' : '' }}>{{ $itemLahan->NOP }}</option>
                                                                        @endforeach  
                                                                    </select>                                                                
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label for="nik">NIK yang Mengajukan</label>
                                                                    <input type="text" class="form-control" name="nik" id="nik" value="{{ $pengajuan->nik }}" readonly>
                                                                </div>                                               
                                                            </div>
                                                            <div class="col-md-6 col-12">
                                                                <div class="form-group mb-3">
                                                                    <label for="luasan">Luasan (Ha)</label>
                                                                    <input type="text" class="form-control" name="luasan" id="luasan" value="{{ $pengajuan->luasan }}" required>
                                                                </div> 
                                                                <div class="form-group mb-3">
                                                                    <label for="tahun">Tahun Pengajuan</label>
                                                                    <input type="number" class="form-control" name="tahun" id="tahun" value="{{ $pengajuan->tahun }}" required>
                                                                </div> 
                                                                <div class="form-group mb-3">
                                                                    <label for="komoditi">Komoditi</label>
                                                                    <input type="text" class="form-control" name="komoditi" id="komoditi" value="{{ $pengajuan->komoditi }}" required>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                        <div class="text-end mt-3">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>                               
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal Delete -->
                                <div class="modal fade" id="deleteModal{{$pengajuan->id_pengajuan}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$pengajuan->id_pengajuan}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-center">
                                            <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <i class="bi bi-trash3 icon-modal"></i>
                                                <h5 class="mt-3">Yakin Ingin Menghapus Data Pengajuan Ini?</h5>
                                                <p>Data ini akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
                                            </div>
                                            <div class="modal-footer border-0 d-flex justify-content-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('pengajuan.destroy', $pengajuan->id_pengajuan) }}" method="post" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        @endif
    </div>



{{-- <div class="form-crud">
    <div class="add">
        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Pengajuan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="nop">NOP</label>
                                <select class="form-select form-select-sm" aria-label="Small select example" id="nop" name="nop">
                                    <option value="">-- Pilih NOP lahan --</option>
                                    @foreach($lahan as $lahanItem)
                                    <option value="{{ $lahanItem->NOP }}" data-luasan="{{ $lahanItem->Luas }}">{{ $lahanItem->NOP }} - {{ $lahanItem->Luas }} Ha</option>
                                    @endforeach                                
                                </select>
                            </div>                            <div class="form-group mb-3">
                                <label for="nik">NIK yang mengajukan</label>
                                <input type="text" class="form-control" name="nik" id="nik" value="{{ Auth::guard('petani')->user()->nik }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="luasan">Luasan (Ha)</label>
                                <input type="text" class="form-control" name="luasan" id="luas" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="tahun">Tahun Pengajuan</label>
                                <input type="number" class="form-control" name="tahun" id="tahun" value="<?php echo date('Y'); ?>" required>
                            </div> 
                            <div class="form-group mb-3">
                                <label for="komoditi">Komoditi</label>
                                <input type="text" class="form-control" name="komoditi" id="komoditi" required>
                            </div>                                                

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="form-crud">
        <div class="add">
            <div class="modal fade" id="add" tabindex="-1" aria-labelledby="modalLabelPupuk" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalLabelPupuk">Tambah Data Pengajuan</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf                                
                                <div class="row">
                                    <div class="col-md-8 col-12">
                                        <div class="form-group mb-3">
                                            <label for="nik">NIK yang mengajukan</label>
                                            <input type="text" class="form-control" name="nik" id="nik" value="{{ Auth::guard('petani')->user()->nik }}" readonly>
                                        </div>
                                    </div> 
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mb-3">
                                            <label for="tahun">Tahun Pengajuan</label>
                                            <input type="number" class="form-control" name="tahun" id="tahun" value="<?php echo date('Y'); ?>" required>
                                        </div> 
                                    </div>
                                </div>
                                @foreach($lahan as $lahanItem)
                                @php
                                    $luasan = $lahanItem->Luas;
                                    if ($luasan > 2.000) {
                                        $luasan = 2.000;
                                    }
                                @endphp
                                <div id="pupuk-container">
                                    <div class="row ">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group mb-3">
                                                <label for="nop">NOP</label>
                                                <input type="text" class="form-control" name="nop[]" id="nop"value="{{ $lahanItem->NOP }}" readonly>
                                            </div>                                              
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group mb-3">
                                                <label for="luasan">Luasan (Ha)</label>
                                                <input type="text" class="form-control" name="luasan[]" id="luas" value="{{ $luasan }}" required >
                                            </div> 
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group mb-3">
                                                <label for="komoditi">Komoditi</label>
                                                <input type="text" class="form-control" name="komoditi[]" id="komoditi" >
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                @endforeach                                
                                
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <p>*Pastikan semua data lahan anda sudah tertera, jika belum lengkapi data lahan</p>
                                    </div>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nopDropdown = document.getElementById("nop");
        const luasanField = document.getElementById("luas");

        nopDropdown.addEventListener("change", function() {
            // Ambil opsi yang dipilih
            const selectedOption = nopDropdown.options[nopDropdown.selectedIndex];
            // Ambil nilai data-luasan dari opsi yang dipilih
            let luasanValue = selectedOption.getAttribute("data-luasan");
            // Isi field luasan dengan nilai data-luasan
            console.log(luasanValue);
            if (luasanValue > 2.000) {
                luasanValue = 2.000;
                luasanField.value = luasanValue || ""; // Kosongkan jika tidak ada nilai
            } else {
                luasanField.value = luasanValue || ""; // Kosongkan jika tidak ada nilai
            }

        });
    });
</script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/custom.js"></script>

@endpush


