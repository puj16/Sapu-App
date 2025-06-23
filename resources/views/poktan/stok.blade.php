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
    <h1 class="h2 mb-0">Stok Pupuk yang Datang</h1>
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
                            <th scope="col">Tahun</th>
                            <th scope="col">Periode</th>
                            <th scope="col">Pupuk</th>
                            <th scope="col">Pupuk yang Datang (Kg)</th>
                            <th scope="col">Pupuk Tersisa (Kg)</th>
                            <th scope="col"> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stok as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->tahun }}</td>
                            <td>{{ $item->periode }}</td>
                            <td>{{ $item->pupuk->nama_pupuk }}</td>
                            <td>{{ number_format($item->pupuk_datang, 0, ',', '.') }}</td>                            
                            <td>{{ number_format($item->pupuk_tersisa, 0, ',', '.') }}</td>
                            <td> 
                                <div class="d-flex">
                                <form action="{{ route('stok.sync') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="hidden" name="tahun" value="{{ $item->tahun }}">
                                    <input type="hidden" name="periode" value="{{ $item->periode }}">
                                    <input type="hidden" name="pupuk" value="{{ $item->id_pupuk }}">
                                    <input type="hidden" name="pupuk_datang" value="{{ $item->pupuk_datang }}">
                                    <button type="submit" class="btn btn-warning me-1" style="color: white"><i class="bi bi-arrow-repeat" title="Sinkronisasi Data "></i></button>
                                </form>
                                <button class="btn btn-danger" style="color: white" data-bs-toggle="modal" data-bs-target="#deleteModal{{$item->id}}" ><i class="bi bi-trash3" title="Hapus Data"></i></button>

                                </div>
                            </td>
                        </tr>
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
                                        <form action="{{ route('stok.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $item->id }}">
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
            <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabelPupuk">Tambah Data Pupuk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('stok.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="tahun">Tahun</label>
                                        <input type="text" class="form-control" name="tahun" id="tahunadd" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="periode">Periode</label>
                                        <input type="text" class="form-control" name="periode" id="periodeadd" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="pupuk">Pupuk</label>
                                    <select class="form-select" name="pupuk" id="pupukadd" required>
                                        <option>-- Pilih pupuk --</option>
                                        @foreach($pupuk as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_pupuk }}</option>
                                        @endforeach   
                                    </select>                                                                
                                </div> 
                            </div>
                            <div class="form-group mb-3">
                                <label for="pupuk_datang">Total Pupuk Datang (Kg) <span style="color: red">*Ubah jika tidak sesuai total pupuk yang datang</span> </label>
                                <input type="number" class="form-control" name="pupuk_datang" id="pupuk_datang" value="" required min="0">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tahunInput = document.getElementById('tahunadd');
    const periodeInput = document.getElementById('periodeadd');
    const pupukSelect = document.getElementById('pupukadd');
    const pupukDatangInput = document.getElementById('pupuk_datang');
    const rdkkData = @json($rdkk);

    // Fungsi hitung total pupuk datang berdasarkan inputan saat ini
    function hitungTotalPupukDatang() {
        const tahun = tahunInput.value.trim();
        const periode = periodeInput.value.trim();
        const idPupuk = pupukSelect.value;

        if (!tahun || !periode || !idPupuk || idPupuk === '-- Pilih pupuk --') {
            pupukDatangInput.value = 0;
            console.log('Total Pupuk yang Datang: 0 (input belum lengkap)');
            return;
        }

        let totalPupukDatang = 0;
        rdkkData.forEach(item => {
            if (item.tahun == tahun && item.periode == periode && item.id_pupuk == idPupuk) {
                const totalVolPupuk = (item.volume_pupuk_mt1 || 0) + (item.volume_pupuk_mt2 || 0) + (item.volume_pupuk_mt3 || 0);
                totalPupukDatang += totalVolPupuk;
            }
        });

        pupukDatangInput.value = totalPupukDatang;
        console.log('Total Pupuk yang Datang:', totalPupukDatang);
    }

    // Pasang event listener untuk input tahun, periode, dan change pupuk
    tahunInput.addEventListener('input', hitungTotalPupukDatang);
    periodeInput.addEventListener('input', hitungTotalPupukDatang);
    pupukSelect.addEventListener('change', hitungTotalPupukDatang);
});
</script>

@endpush