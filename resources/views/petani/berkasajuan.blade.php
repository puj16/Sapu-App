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

<div class="title-group mb-3">
    <h1 class="h2 mb-0">Berkas Pengajuan</h1>
</div>

<div class="row my-4">
    <div class="col-lg-5 col-12">
        <div class="custom-block custom-block-contact">
            <h6 class="mb-4">Detail data pengajuan</h6>

            <p class="d-flex flex-wrap mb-2">
                <strong>ID Ajuan:</strong>
                <a  class="ms-2">
                    {{$pengajuan->id_pengajuan}}
                </a>
            </p>

            <p class="d-flex flex-wrap mb-2">
                <strong>NOP:</strong>
                <a  class="ms-2">
                    {{$pengajuan->NOP}}
                </a>
            </p>
            <p class="d-flex flex-wrap mb-2">
                <strong>NIK:</strong>
                <a  class="ms-2">
                    {{$pengajuan->nik}}
                </a>
            </p>
            <p class="d-flex flex-wrap mb-2">
                <strong>Luasan:</strong>
                <a  class="ms-2">
                    {{$pengajuan->luasan}}
                </a>
            </p>
            <p class="d-flex flex-wrap mb-2">
                <strong>Komoditi:</strong>
                <a  class="ms-2">
                    {{$pengajuan->komoditi}}
                </a>
            </p>
            <p class="d-flex flex-wrap mb-2">
                <strong>Tahun:</strong>
                <a  class="ms-2">
                    {{$pengajuan->tahun}}
                </a>
            </p>
            <p class="d-flex flex-wrap mb-2">
                <strong>Status:</strong>
                <a  class="ms-2">
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
                </a>
            </p>
            @if ((int) $pengajuan->status_validasi === 2)
            <p class="d-flex flex-wrap mb-2">
                <strong>Catatan:</strong>
                <a  class="ms-2">
                    {{ $pengajuan->catatan }}
                </a>
            </p>
            @endif

            <a href="{{url('/pengajuan-index')}}" class="btn custom-btn custom-btn-bg-white mt-3">
                <i class="bi-arrow-up-left-circle-fill me-2"></i>Back
            </a>
        </div>
    </div>
    <div class="col-lg-7 col-12">
        <div class="custom-block bg-white mb-0 pb-0">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="true">KTP</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab" aria-controls="password-tab-pane" aria-selected="false">KK</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification-tab-pane" type="button" role="tab" aria-controls="notification-tab-pane" aria-selected="false">SPPT</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <h6 class="mb-4">KTP</h6>

                    <form class="custom-form profile-form" action="{{ route('ktp.update', ['nik' => $petani->nik, 'id_pengajuan' => $pengajuan->id_pengajuan]) }}" method="POST" enctype="multipart/form-data">
                        @csrf  
                        @method('PUT')  
                        @if ($petani->ktp == 'Belum terisi')
                            <a href="{{ asset('images/profile/no-data-found.jpg') }}"  class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2" src="{{ asset('images/profile/no-data-found.jpg') }}" class="artist-image img-fluid">
                        @else
                            <a href="{{ asset('storage/assets/doc/ktp/' . $petani->ktp) }}" alt="{{ $petani->ktp }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2"src="{{ asset('storage/assets/doc/ktp/' . $petani->ktp) }}" alt="{{ $petani->ktp }}" class="artist-image img-fluid">
                        @endif

                        <div class="input-group mb-1">
                            <input type="file" class="form-control" id="inputGroupFile02" name="ktp" accept="image/*">
                        </div>

                        <div class="d-flex">
                            <button type="button" class="form-control me-3">
                                <a href="{{ url('/petani-profile') }}">
                                Go to Petani
                            </button>

                            <button type="submit" class="form-control ms-2">
                                Update
                            </button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab" tabindex="0">
                    <h6 class="mb-4">KK</h6>

                    <form class="custom-form profile-form" action="{{ route('kk.update', ['nik' => $petani->nik, 'id_pengajuan' => $pengajuan->id_pengajuan]) }}" method="POST" enctype="multipart/form-data">
                        @csrf  
                        @method('PUT')  
                        @if ($petani->kk == 'Belum terisi')
                            <a href="{{ asset('images/profile/no-data-found.jpg') }}"  class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2" src="{{ asset('images/profile/no-data-found.jpg') }}" class="artist-image img-fluid">
                        @else
                            <a href="{{ asset('storage/assets/doc/kk/' . $petani->kk) }}" alt="{{ $petani->kk }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2"src="{{ asset('storage/assets/doc/kk/' . $petani->kk) }}" alt="{{ $petani->kk }}" class="artist-image img-fluid">
                        @endif

                        <div class="input-group mb-1">
                            <input type="file" class="form-control" id="inputGroupFile02" name="kk" accept="image/*"> 
                        </div>

                        <div class="d-flex">
                            <button type="button" class="form-control me-3">
                                <a href="{{ url('/petani-profile') }}">
                                Go to Petani
                            </button>

                            <button type="submit" class="form-control ms-2">
                                Update
                            </button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="notification-tab-pane" role="tabpanel" aria-labelledby="notification-tab" tabindex="0">
                    <h6 class="mb-4">SPPT</h6>

                    <form class="custom-form profile-form" action="{{ route('sppt.update', ['NOP' => $lahan->NOP, 'id_pengajuan' => $pengajuan->id_pengajuan]) }}" method="POST" enctype="multipart/form-data">
                        @csrf  
                        @method('PUT')  
                        <a href="{{ asset('storage/assets/doc/sppt/' . $lahan->Foto_SPPT) }}"" alt="{{ $lahan->Foto_SPPT }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                        <img class="sppt-image-wrap mb-2" src="{{ asset('storage/assets/doc/sppt/' . $lahan->Foto_SPPT) }}" alt="{{ $lahan->Foto_SPPT }}">

                        <div class="input-group mb-1">
                            <input type="file" class="form-control" id="inputGroupFile02" name="sppt" accept="image/*">
                        </div>

                        <div class="d-flex">
                            <button type="button" class="form-control me-3">
                                <a href="{{ url('/lahan') }}">
                                Go to lahan
                            </button>

                            <button type="submit" class="form-control ms-2">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
</div>

@endsection
@push('scripts')
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/custom.js"></script>

@endpush