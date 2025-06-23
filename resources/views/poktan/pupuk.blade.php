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
                <table class="account-table table" id="pupukTable">
                    <thead>
                        <tr>
                            <th scope="col">NO</th>
                            <th scope="col">Nama Pupuk</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pupuk as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_pupuk }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge text-bg-dark" data-bs-toggle="modal" data-bs-target="#editModal{{$item->id}}" title="Edit">
                                    <i class="bi-pencil-square icon"></i>
                                </span>
                                <span class="badge text-bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$item->id}}" title="Hapus">
                                    <i class="bi bi-trash3 icon"></i>
                                </span>
                            </td>
                        </tr>
                
                        <div class="modal fade" id="editModal{{$item->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$item->id}}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editModalLabel{{$item->id}}">Edit Data Pupuk</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('pupuk.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group mb-3">
                                                <label for="nama_pupuk{{$item->id}}">Nama Pupuk</label>
                                                <input type="text" class="form-control" name="nama_pupuk" id="nama_pupuk{{$item->id}}" value="{{ $item->nama_pupuk }}" required>
                                            </div>
                                            
                                            <div class="form-group mb-3">
                                                <label for="harga{{$item->id}}">Harga (Rp)</label>
                                                <input type="text" class="form-control" name="harga" id="harga{{$item->id}}" value="{{ number_format($item->harga, 0, ',', '.') }}" required oninput="formatRupiah(this)">
                                            </div>

                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteModal{{$item->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$item->id}}" aria-hidden="true">
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
                                        <form action="{{ route('pupuk.destroy', $item->id) }}" method="POST">
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
        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="modalLabelPupuk" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabelPupuk">Tambah Data Pupuk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('pupuk.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="nama_pupuk">Nama Pupuk</label>
                                <input type="text" class="form-control" name="nama_pupuk" id="nama_pupuk" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="harga">Harga (Rp)</label>
                                <input type="text" class="form-control" name="harga" id="harga" required min="0">
                            </div>  
                            
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>    
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
    $(document).ready(function () {
        var table = $('#pupukTable').DataTable({
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
        $('#pupukTable_filter input').attr('placeholder', 'Search');
    });
</script>
@endpush