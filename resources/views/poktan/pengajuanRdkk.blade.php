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
    <h1 class="h2 mb-0">Data RDKK</h1>
    @if(auth()->user()->role == 0)
    <div class="profile-rounded">
        <a href="#" class="add-data" data-bs-toggle="modal" data-bs-target="#add1">
            <i class="profile-rounded-icon bi-plus"></i>
        </a>
    </div>
    @endif
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
                    @if(auth()->user()->role == 0)
                    <form method="GET" action="{{ route('rdkk.index') }}" class="row g-2 d-flex justify-content-end">
                    @elseif (auth()->user()->role == 1)
                    <form method="GET" action="{{ route('index.rdkk') }}" class="row g-2 d-flex justify-content-end">
                    @endif
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

                    <div id="customButtonWrapper" class="d-inline-block ms-2">
                        @if(auth()->user()->role == 0)
                        <form action="{{ route('rdkk.report') }}" method="get">
                        @elseif (auth()->user()->role == 1)
                        <form action="{{ route('report.rdkk') }}" method="get">
                        @endif
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="periode" value="{{ $periode }}">
                            <input type="hidden" name="komoditi" value="{{ $komoditi }}">
                            <input type="hidden" name="jenis" value="pengajuan">
                            <button type="submit" class="btn btn-primary" id="btn-tambah"><i class="bi bi-filetype-pdf icon"></i></button>
                        </form>
                    </div>
                    <div id="customButton" class="d-inline-block ms-2">
                        @if(auth()->user()->role == 0)
                        <form action="{{ route('rdkk.export') }}" method="get">
                        @elseif (auth()->user()->role == 1)
                        <form action="{{ route('export.rdkk') }}" method="get">
                        @endif
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="periode" value="{{ $periode }}">
                            <input type="hidden" name="komoditi" value="{{ $komoditi }}">
                            <button type="submit" class="btn btn-success" id="btn-tambah"><i class="bi bi-filetype-xls icon"></i></button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="account-table table " id="rdkkTable">
                    
                            <thead style="border: 1px solid black;text-align: center; ">
                                <tr>
                                    <th scope="col" rowspan="3">NO</th>
                                    <th scope="col" rowspan="3">NIK</th>
                                    <th scope="col" rowspan="3">Nama</th>
                                    <th scope="col" rowspan="3">Rencana Tanam (Ha)</th>
                                    <th scope="col" colspan="{{ $uniqueNamaPupuk->count() * 4 }}">Alokasi Pupuk Bersubsidi (Kg)</th>
                                </tr>
                                <tr>
                                    @foreach ($uniqueNamaPupuk as $namaPupuk)
                                    <th scope="col" colspan="4">{{ $namaPupuk }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($uniqueNamaPupuk as $mtPupuk)
                                    <th scope="col">MT-1</th>
                                    <th scope="col">MT-2</th>
                                    <th scope="col">MT-3</th>
                                    <th scope="col">JML</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">  
                            @foreach ($rdkk as $nik => $data)
                                        @php
                                            $firstItem = $data->first(); // Ambil item pertama dalam grup
                                            $status = $firstItem->status_penyaluran;
                                            $tahundata = $firstItem->tahun;
                                            $periodedata = $firstItem->periode;
                                            $komoditidata = $firstItem->komoditi;
                                        @endphp
                                        <tr>
                                            <td >{{ $loop->iteration }}</td>
                                            <td >
                                                {{ $firstItem->nik }}
                                            </td>                                             
                                            <td >
                                                {{ $firstItem->petani->nama ?? 'Petani tidak ditemukan' }}
                                            </td>
                                            <td >
                                                {{ $firstItem->pengajuan->luasan ?? 'Pengajuan tidak ditemukan' }}
                                            </td>
                                
                                            @foreach ($uniqueNamaPupuk as $pupukNama)
                                                @php
                                                    $rdkkItem = $data->where('pupuk.nama_pupuk', $pupukNama)->first();
                                                @endphp
                                                <td >{{ $rdkkItem->pengajuan_mt1 ?? "X" }}</td>
                                                <td >{{ $rdkkItem->pengajuan_mt2 ?? "X" }}</td>
                                                <td >{{ $rdkkItem->pengajuan_mt3 ?? "X" }}</td>
                                                <td >{{ 
                                                    ($rdkkItem->pengajuan_mt1 ?? 0) + 
                                                    ($rdkkItem->pengajuan_mt2 ?? 0) + 
                                                    ($rdkkItem->pengajuan_mt3 ?? 0) 
                                                }}</td>
                                            @endforeach
                                        </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center my-4">
                        <form action="{{ route('rdkk.index') }}" method="GET">
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="periode" id="" value="{{ $periode }}"> 
                            <input type="hidden" name="komoditi" id="" value="{{ $komoditi }}">   
                        <button type="submit" class="view-button me-1" >
                            Lihat & Sesuaikan RDKK <i class="bi-arrow-up-right-circle-fill ms-2"></i>
                        </button>
                        </form>

                    </div>
        @endif

        </div>
    </div>

<div class="form-crud">
    <div class="add">
        <div class="modal fade" id="add1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data RDKK</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('rdkk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="tahun">Tahun</label>
                                @if ($tahun == null)
                                    <input type="text" class="form-control" name="tahun" id="tahun" value="<?php echo date('Y'); ?>" placeholder="<?php echo date('Y'); ?>" required>
                                @else
                                    <input type="text" class="form-control" name="tahun" id="tahun" value="{{ $tahun }}" placeholder="<?php echo date('Y'); ?>" required>
                                @endif
                            </div> 
                            <div class="form-group mb-3">
                                <label for="periode">Periode</label>
                                <input type="text" class="form-control" name="periode" id="periode" value="" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nop">Komoditi</label>
                                <select class="form-select form-select-sm" aria-label="Small select example" id="nop" name="komoditi">
                                    <option value="">-- Pilih Komoditi --</option>
                                    <option value="Padi">Padi</option>
                                    <option value="Jagung">Jagung</option>
                                    <option value="Tebu">Tebu</option>
                                </select>                            
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="edit">
        <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="modalLabelPupuk" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabelPupuk">Ubah Volume Pupuk </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('rdkk.update1') }}" method="POST">
                            @csrf
                            @method('PUT')                            
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group mb-3">
                                        <label for="tahun">Tahun</label>
                                        <input type="text" class="form-control" name="tahun" id="tahun" value="{{ $tahun }}"  required readonly>
                                    </div> 
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group mb-3">
                                        <label for="periode">Periode</label>
                                        <input type="text" class="form-control" name="periode" id="periode" value="{{ $periode }}" required readonly>
                                    </div>
                                </div> 
                                <div class="col-md-4 col-12">
                                    <div class="form-group mb-3">
                                        <label for="komoditi">Komoditi</label>
                                        <input type="text" class="form-control" name="komoditi" id="komoditi" value="{{ $komoditi }}" required readonly>
                                    </div> 
                                </div>
                            </div>

                            @foreach ($uniqueNamaPupuk as $pupukrow)
                            <div id="pupuk-container">
                                <div class="row ">
                                    <div class="col-md-9 col-12">
                                        <div class="form-group mb-3">
                                            <label for="pupuk">Pupuk</label>
                                            <input type="text" class="form-control" value="{{ $pupukrow }}" readonly>
                                            <input type="hidden" name="pupuk[]" value="{{ $pupuk->where('nama_pupuk', $pupukrow)->first()->id ?? '' }}">                                        </div>                                              
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group mb-3">
                                            <label for="mt3">Vol pupuk /  Ha </label>
                                            <input type="text" class="form-control" name="vol[]" required>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            @endforeach                                
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">Save</button>    
                            </div>

                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal Simpan Permanen --}}

    
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var table = $('#rdkkTable').DataTable({
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
        $('#rdkkTable_filter input').attr('placeholder', 'Search');

        $('#rdkkTable_filter').append($('#customButtonWrapper'));
        $('#rdkkTable_filter').append($('#customButton'));

    
        // Nonaktifkan select pengajuan di awal
        });
</script>

<script>
    $(document).ready(function () {

        $('#nikselect').select2({
            width: '100%' ,
            dropdownParent: $('#add'),
            placeholder: '-- Pilih Petani --',
            allowClear: true
        });

    $('#pengajuanselect').select2({
        width: '100%' ,
        dropdownParent: $('#add'),
        placeholder: '-- Pilih Pengajuan --',
        allowClear: true
    });
    $('#nikselect').on('select2:select', function (e) {
    const selectedNik = e.params.data.id;
    const pengajuanData = @json($pengajuan);
    console.log(pengajuanData);
    const pengajuanDropdown = $('#pengajuanselect');
    
    // Reset pengajuan
    pengajuanDropdown.prop('disabled', !selectedNik);
    pengajuanDropdown.empty().append('<option value="">-- Pilih Pengajuan --</option>');

        if (selectedNik) {
            console.log('c'+ selectedNik);
            pengajuanData.forEach(item => {
                console.log(item.nik);
                if (item.nik === selectedNik) {
                    const option = new Option(`Komoditi: ${item.komoditi} NOP: ${item.NOP} Luasan: ${item.luasan}`, item.id_pengajuan, false, false);
                    option.setAttribute('data-nik', item.nik);
                    option.setAttribute('data-komoditi', item.komoditi);
                    pengajuanDropdown.append(option);
                }           
            });

            pengajuanDropdown.trigger('change'); // optional
        }
    });

});
</script>


<script>
    $(document).ready(function () {
        $(document).on("change", "select[name='nik']", function () {
            let modal = $(this).closest(".modal");
            
            // Cek apakah modal ini bukan modal Add
            if (modal.attr("id") === "add") return;

            let selectedNIK = $(this).val();
            let pengajuanDropdown = modal.find("select[name='id_pengajuan']");

            // Filter opsi dalam dropdown pengajuan
            pengajuanDropdown.find("option").each(function () {
                let optionNIK = $(this).data("nik");

                if (!selectedNIK || optionNIK == selectedNIK) {
                    $(this).show().prop("disabled", false);
                } else {
                    $(this).hide().prop("disabled", true);
                }
            });

            // Pilih opsi pertama yang cocok jika ada
            let firstOption = pengajuanDropdown.find("option:not([disabled])").first();
            if (firstOption.length) {
                pengajuanDropdown.val(firstOption.val()).prop("disabled", false);
            } else {
                pengajuanDropdown.val("").prop("disabled", true);
            }

            pengajuanDropdown.selectpicker("refresh");
        });

        // Saat modal ditampilkan, pastikan dropdown pengajuan sesuai dengan NIK yang sudah terpilih
        $(document).on("shown.bs.modal", ".modal", function () {
            let modal = $(this);
            
            // Hindari modal Add
            if (modal.attr("id") === "add") return;

            let selectedNIK = modal.find("select[name='nik']").val();
            let pengajuanDropdown = modal.find("select[name='id_pengajuan']");

            pengajuanDropdown.find("option").each(function () {
                let optionNIK = $(this).data("nik");
                if (!selectedNIK || optionNIK == selectedNIK) {
                    $(this).show().prop("disabled", false);
                } else {
                    $(this).hide().prop("disabled", true);
                }
            });

            let firstOption = pengajuanDropdown.find("option:not([disabled])").first();
            if (firstOption.length) {
                pengajuanDropdown.val(firstOption.val()).prop("disabled", false);
            } else {
                pengajuanDropdown.val("").prop("disabled", true);
            }

            pengajuanDropdown.selectpicker("refresh");
        });
    });
</script>

<script>
    $(document).ready(function() {
    // Fungsi untuk memperbarui opsi dropdown pupuk dalam container tertentu
    function updatePupukOptionsMain() {
        let selectedPupuk = [];

        // Kumpulkan semua pupuk yang sudah dipilih di dalam #pupuk-container
        $("#pupuk-container select[name='pupuk[]']").each(function() {
            let val = $(this).val();
            if (val) {
                selectedPupuk.push(val);
            }
        });

        // Perbarui setiap select dalam #pupuk-container agar opsi yang sudah dipilih tidak muncul di dropdown lain
        $("#pupuk-container select[name='pupuk[]']").each(function() {
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

    // Event: Saat memilih pupuk di form utama, perbarui dropdown dalam form utama saja
    $(document).on("change", "#pupuk-container select[name='pupuk[]']", function() {
        updatePupukOptionsMain();
    });

    // Tambah baris baru di form utama
    $(document).on("click", ".add-row", function(e) {
        e.preventDefault();
        let row = $(this).closest(".row").clone();
        row.find("input").val("");
        row.find("select").val("");
        row.find(".add-row").parent().html('<a href="#" class="remove-row"><i class="rounded-icon bi-x"></i></a>');
        $("#pupuk-container").append(row); // Tambah ke container utama
        updatePupukOptionsMain(); // Perbarui opsi dropdown
    });

    // Hapus baris di form utama
    $(document).on("click", ".remove-row", function(e) {
            e.preventDefault();
            $(this).closest(".row").remove(); // Hapus row
            updatePupukOptionsMain(); // Perbarui opsi dropdown
    });

    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('confirmCheckbox');
        const submitButton = document.getElementById('submitButton');

        checkbox.addEventListener('change', function () {
            submitButton.disabled = !this.checked;
        });
    });
</script>
@endpush


