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
        
                <!-- Form Pilihan -->
                <div class="col-lg-9 col-md-8 col-sm-12 d-flex justify-content-end">
                    <form method="GET" action="{{ route('rdkk.show') }}" class="row g-2 d-flex justify-content-end">
                        <!-- Dropdown Tahun -->
                        <div class="col-lg-5 col-md-6 col-sm-12">
                            <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($tahunrdkk->pluck('tahun')->unique()->sortDesc() as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <!-- Dropdown Status Validasi -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <select name="komoditi" id="komoditi" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($komoditirdkk->pluck('komoditi')->unique()->sortDesc() as $k)
                                    <option value="{{ $k }}" {{ $komoditi == $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>

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
        </div>

        @if ($rdkk->isEmpty())
            <div class="custom-block bg-white mt-3">
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <p class="mb-0 text-black">Data tidak tersedia</p>
                </div>
            </div>
        @else
                <div class="custom-block bg-white">
                    <p class="d-flex flex-wrap mt-3 mb-4">
                        <strong class="me-2">Periode:</strong>
                        <span>{{ $periode}}</span>
                    </p>
                    <p class="d-flex flex-wrap mt-3 mb-4">
                        <strong class="me-2">Komoditi:</strong>
                        <span>{{ $komoditi }}</span>
                    </p>

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
                                            <i class="bi-trash3 me-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{$firstItem->id_pengajuan}}_delete" style="cursor: pointer;"></i>
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
        @endif

        </div>
    </div>


    <div class="form-crud">
        <div class="add">
            <div class="modal fade" id="add" tabindex="-1" aria-labelledby="modalLabelPupuk" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalLabelPupuk">Tambah Data Pupuk</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('rdkk.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mb-3">
                                            <label for="tahun">Tahun</label>
                                            <input type="text" class="form-control" name="tahun" id="tahun" value="{{ $tahun }}" required>
                                        </div> 
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mb-3">
                                            <label for="periode">Periode</label>
                                            <input type="text" class="form-control" name="periode" id="periode" value="{{ $periode }}" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mb-3">
                                            <label for="komoditi">Komoditi</label>
                                            <input type="text" class="form-control" name="komoditi" id="komoditi" value="{{ $komoditi }}" required>
                                        </div> 
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="nik">NIK</label>
                                            <select id="nikselect" class="selectpicker" data-live-search="true" name="nik">
                                                <option value="">-- Pilih Petani --</option>
                                                @foreach($petani as $itemnik)
                                                    <option value="{{ $itemnik->nik }}">{{ $itemnik->nik }}</option>
                                                @endforeach   
                                            </select>                                                                
                                        </div>
                                
                                        <div class="form-group mb-3">
                                            <label for="id_pengajuan">ID Pengajuan <span style="color: rgb(216, 2, 2)">*pastikan untuk memilih NIK terlebih dahulu</span></label>
                                            <select id="pengajuanselect" class="selectpicker" data-live-search="true" name="id_pengajuan">
                                                <option value="">-- Pilih Pengajuan --</option>
                                                @foreach($pengajuan as $item)
                                                    <option value="{{ $item->id_pengajuan }}" 
                                                            data-nik="{{ $item->nik }}" 
                                                            data-komoditi="{{ $item->komoditi }}">
                                                           KOmoditi:{{ $item->komoditi }} NOP:{{ $item->NOP }} Luasan:{{ $item->luasan }}
                                                    </option>
                                                @endforeach   
                                            </select>
                                                                                                           
                                        </div>
                                    </div>
                                </div>

                                <div id="pupuk-container">
                                    <div class="row ">
                                        <div class="col-md-5 col-12">
                                            <div class="form-group mb-3">
                                                <label for="pupuk">Pupuk</label>
                                                <select class="form-select" name="pupuk[]">
                                                    <option>-- Pilih pupuk --</option>
                                                    @foreach($pupuk as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_pupuk }}</option>
                                                    @endforeach   
                                                </select>                                                                
                                            </div>                                              
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group mb-3">
                                                <label for="mt1">MT-1</label>
                                                <input type="text" class="form-control" name="mt1[]" required>
                                            </div> 
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group mb-3">
                                                <label for="mt2">MT-2</label>
                                                <input type="text" class="form-control" name="mt2[]" required>
                                            </div> 
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group mb-3">
                                                <label for="mt3">MT-3</label>
                                                <input type="text" class="form-control" name="mt3[]" required>
                                            </div> 
                                        </div>
                                        <div class="col-md-1 col-12">
                                            <div class="form-group mb-3">
                                                <a href="#" class="add-row">
                                                    <i class="rounded-icon bi-plus"></i>
                                                </a>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                                                                
                                <!-- Tempat untuk baris tambahan -->
                                <div id="additional-rows"></div>
                                
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
    $(document).ready(function () {
        var table = $('#scheduleTable1').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "lengthMenu": [5, 10, 25],
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


                $('#nikselect, #pengajuanselect').selectpicker(); // Inisialisasi selectpicker
    
        // Nonaktifkan select pengajuan di awal
            $("#pengajuanselect").prop("disabled", true).selectpicker("refresh");
    
        $("#nikselect").on("change", function () {
            var selectedNIK = $(this).val(); // Ambil NIK yang dipilih
    
            if (selectedNIK) {
                $("#pengajuanselect").prop("disabled", false); // Aktifkan dropdown pengajuan
            } else {
                $("#pengajuanselect").prop("disabled", true).val(""); // Nonaktifkan dan reset jika NIK tidak dipilih
            }
    
            $("#pengajuanselect option").each(function () {
                var optionNIK = $(this).data("nik"); // Ambil data-nik dari option
    
                if (!selectedNIK || optionNIK == selectedNIK) {
                    $(this).show();  // Tampilkan jika cocok
                } else {
                    $(this).hide();  // Sembunyikan jika tidak cocok
                }
            });
    
            $("#pengajuanselect").selectpicker("refresh"); // Refresh selectpicker
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Fungsi untuk memperbarui opsi pada setiap dropdown pupuk
        function updatePupukOptions() {
            let selectedPupuk = [];
            
            // Kumpulkan semua pupuk yang sudah dipilih
            $("select[name='pupuk[]']").each(function() {
                let val = $(this).val();
                if (val) {
                    selectedPupuk.push(val);
                }
            });

            // Perbarui setiap select agar opsi yang sudah dipilih tidak muncul di dropdown lain
            $("select[name='pupuk[]']").each(function() {
                let currentValue = $(this).val();
                $(this).find("option").each(function() {
                    let optionValue = $(this).val();
                    
                    if (optionValue && selectedPupuk.includes(optionValue) && optionValue !== currentValue) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
        }

        // Event: Saat memilih pupuk, perbarui opsi di dropdown lain
        $(document).on("change", "select[name='pupuk[]']", function() {
            updatePupukOptions();
        });

        // Tambah baris baru saat tombol plus ditekan
        $(document).on("click", ".add-row", function(e) {
            e.preventDefault();
            let row = $(this).closest(".row").clone(); // Clone row terakhir
            row.find("input").val(""); // Kosongkan input field
            row.find("select").val(""); // Reset select dropdown
            row.find(".add-row").parent().html('<a href="#" class="remove-row"><i class="rounded-icon bi-x"></i></a>'); // Ganti tombol jadi hapus
            $("#pupuk-container").append(row); // Tambah ke container utama
            updatePupukOptions(); // Perbarui opsi dropdown
        });

        // Hapus baris yang diklik
        $(document).on("click", ".remove-row", function(e) {
            e.preventDefault();
            $(this).closest(".row").remove(); // Hapus row
            updatePupukOptions(); // Perbarui opsi dropdown
        });
    });
</script>
@endpush


