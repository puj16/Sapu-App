@extends('layouts.masterpetugas')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
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
    <h1 class="h2 mb-0">Data Petani</h1>
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
                <table class="account-table table" id="scheduleTable">
                    <thead>
                        <tr>
                            <th scope="col">NO</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Nama Petani</th>
                            <th scope="col">Nomor WA</th>
                            <th scope="col">Status KK</th>
                            <th scope="col">Status KTP</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($petani as $petani)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td scope="row">{{ $petani->nik }}</td>
                            <td scope="row">{{ $petani->nama }}</td>
                            <td scope="row">{{ $petani->wa }}</td>
                            <td scope="row">
                                @if ($petani->kk == 'Belum terisi')
                                    <span class="badge text-bg-danger">Belum Terisi</span>
                                @else
                                    <span class="badge text-bg-success">Sudah Terisi</span>
                                @endif
                            </td>
                            <td scope="row">
                                @if ($petani->ktp == 'Belum terisi')
                                    <span class="badge text-bg-danger">Belum Terisi</span>
                                @else
                                    <span class="badge text-bg-success">Sudah Terisi</span>
                                @endif
                            </td>
                            <td scope="row">
                                <span class="badge text-bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$petani->nik}}">
                                    <i class="bi bi-trash3 icon"></i>
                                </span>
                            </td>
                        </tr>

                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteModal{{$petani->nik}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$petani->nik}}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-center">
                                    <div class="modal-header border-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <i class="bi bi-trash3 icon-modal"></i>
                                        <h5 class="mt-3">Anda Yakin Ingin Menghapus Data Ini?</h5>
                                        <p>Data ini akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
                                    </div>
                                    <div class="modal-footer border-0 d-flex justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{route('petani.destroy', $petani->nik)}}" method="post" class="d-inline-block">
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

<div class="form-crud">
    <div class="add">
        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="data-leModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg center">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Petani</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form action="{{ route('registrasi_post') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="id">Masukkan NIK <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" value="{{ old('nik') }}" name="nik" placeholder="NIK" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="id">Masukkan nama petani <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" value="{{ old('nama') }}" name="nama" placeholder="Nama Petani" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="id">Masukkan nomor WA<span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" value="{{ old('wa') }}" name="wa" placeholder="Nomor WA" required>
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

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var table = $('#scheduleTable').DataTable({
            "paging": true,        // Mengaktifkan pagination
            "searching": true,     // Mengaktifkan fitur pencarian
            "ordering": true,      // Mengaktifkan pengurutan
            "lengthMenu": [5,10, 20, 50], // Menentukan jumlah data per halaman
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
    });
</script>
@endpush