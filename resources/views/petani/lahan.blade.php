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

<div class="title-group mb-3 d-flex align-items-center">
    <h1 class="h2 mb-0">Data Lahan</h1>
    <div class="profile-rounded">
        <a href="#" class="add-data" data-bs-toggle="modal" data-bs-target="#add">
            <i class="profile-rounded-icon bi-plus"></i>
        </a>
    </div>
</div>

<div class="row my-4">
    <div class="col-lg-12 col-12">
        <div class="custom-block bg-white">            
            <div class="table-responsive">
                <table class="account-table table" id="lahanTable">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">NOP</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Luas(Ha)</th>
                            <th scope="col">Status</th>
                            <th scope="col">SPPT</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lahan as $lahan)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td scope="row">{{ $lahan->NOP }}</td>
                            <td scope="row">{{ $lahan->NIK }}</td>
                            <td scope="row">{{ $lahan->Luas }}</td>
                            <td scope="row">
                                <span class="badge text-bg-success">{{ $lahan->status }}</span>
                            </td>
                            <td scope="row">
                                <div class="position-relative">
                                    <img class="table-img" src="{{ asset('storage/assets/doc/sppt/' . $lahan->Foto_SPPT) }}" alt="">
                                    <a href="{{ asset('storage/assets/doc/sppt/' . $lahan->Foto_SPPT) }}" class="bi-eye custom-block-edit-icon position-absolute bottom-0 end-0" title="Lihat gambar"></a>
                                </div>                            
                            </td>
                            <td scope="row">
                                <span class="badge text-bg-dark" data-bs-toggle="modal" data-bs-target="#editModal{{$lahan->NOP}}" title="Edit">
                                    <i class="bi-pencil-square icon"></i>
                                </span>
                                <span class="badge text-bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$lahan->NOP}}" title="Hapus">
                                    <i class="bi bi-trash3 icon"></i>
                                </span>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="edit">
                            <div class="modal fade" id="editModal{{$lahan->NOP}}" tabindex="-1" aria-labelledby="editModalLabel{{$lahan->NOP}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Lahan</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{route('lahan.update', $lahan->NOP)}}" method="post" enctype="multipart/form-data" >
                                                @csrf  
                                                @method('PUT')                                              
                                                <div class="row">
                                                    <div class="col-6 col-sm-6">
                                                      <div class="form-group mb-3">
                                                          <label for="nop" >NOP</label>
                                                          <input type="text" class="form-control" name="nop" id="nop" value="{{ $lahan->NOP }}" readonly>
                                                      </div>
                                                      <div class="form-group mb-3">
                                                          <label for="nik" >NIK</label>
                                                          <input type="text" class="form-control" name="nik" id="nik" value="{{ $lahan->NIK }}">
                                                      </div>
                                                      <div class="form-group mb-3">
                                                          <label for="luas" >Luas (Ha)</label>
                                                          <input type="text" class="form-control" name="luas" id="luas" value="{{ $lahan->Luas }}">
                                                      </div>                                                
                                                            </div>
                                                    <div class="col-6 col-sm-6">
                                                        <div class="form-group mb-3">
                                                            <label for="status">Status</label>
                                                            <select class="form-select form-select-sm" aria-label="Small select example" name="status">
                                                                <option value="">-- Pilih status --</option>
                                                                <option value="Milik" {{ $lahan->status == 'Milik' ? 'selected' : '' }}>Milik</option>
                                                                <option value="Garapan" {{ $lahan->status == 'Garapan' ? 'selected' : '' }}>Garapan</option>
                                                                <option value="Bagi Hasil" {{ $lahan->status == 'Bagi Hasil' ? 'selected' : '' }}>Bagi Hasil</option>
                                                            </select>                                                                
                                                        </div>
                                                      <div class="form-group mb-3">
                                                          <label for="sppt">SPPT</label>
                                                          <input type="file" class="form-control" name="sppt" accept="image/*">
                                                          <div class="form-text text-danger">*Kosongi bila tidak ingin mengupdate ktp</div>
                                                      </div>
                              
                                                    </div>
                                                <div class="col-4 offset-9">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                                </div>                                
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteModal{{$lahan->NOP}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$lahan->NOP}}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-center">
                                    <div class="modal-header border-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <i class="bi bi-trash3 icon-modal"></i>
                                        <h5 class="mt-3">Anda Yakin Ingin Menghapus Data Lahan Ini?</h5>
                                        <p>Data ini akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
                                    </div>
                                    <div class="modal-footer border-0 d-flex justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('lahan.destroy', $lahan->NOP) }}" method="post" class="d-inline-block">
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
    </div>
</div>

@php
    $nikPetani = Auth::guard('petani')->user()->nik;
@endphp
<div class="form-crud">
    <div class="add">
        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Lahan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('lahan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="nop">NOP</label>
                                <input type="text" class="form-control" name="nop" id="nop"  maxlength="18" pattern="[0-9]{18}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 18);">
                            </div>
                            <div class="form-group mb-3">
                                <label for="nik">NIK Pemilik Lahan</label>
                                <input type="text" class="form-control" name="nik" id="nikadd"  maxlength="16" pattern="[0-9]{16}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);" value="{{ $nikPetani }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="luas">Luas (Ha)</label>
                                <input type="text" class="form-control" name="luas" id="luas" required>
                            </div>                                                
                            <div class="form-group mb-3">
                                <label for="luas">Status <span style="color: red">*Pastikan NIK Terisi</span></label>
                                <input type="text" class="form-control" name="status" id="statusadd" required readonly value="Garapan">

                            </div>
                            <div class="form-group mb-3">
                                <label for="sppt">SPPT </label>
                                <input type="file" class="form-control" name="sppt" accept="image/*" required>
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
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/custom.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nikInput = document.getElementById('nikadd');
        const statusSelect = document.getElementById('statusadd');
        const nikPetani = "{{ $nikPetani }}";

        nikInput.addEventListener('input', function () {
            if (nikInput.value === nikPetani) {
                statusSelect.value = "Garapan";
            }else if (nikInput.value === "") {
                statusSelect.value = ""; // Reset jika input kosong
                }
             else {
                statusSelect.value = "Bagi Hasil";
            }
        });
    });
</script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script><script>
    $(document).ready(function () {
        var table = $('#lahanTable').DataTable({
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
        $('#lahanTable_filter input').attr('placeholder', 'Search');
    });
</script>

@endpush