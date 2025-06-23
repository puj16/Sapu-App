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

<div class="title-group mb-3 ">
    <div class = "d-flex align-items-center">
    <h1 class="h2 mb-0">Berkas Pengajuan</h1>
    @if(auth()->user()->role == 0)
    <div class="badge ms-2" style="background:#E63946;">
        <a href="#" class="add-data text-white" data-bs-toggle="modal" data-bs-target="#deleteModal{{$pengajuan->id_pengajuan}}" title="Validasi"  @if($pengajuan->status_validasi != 0) style="pointer-events: none; opacity: 0.5;" @endif>
            Validasi
        </a>
    </div>
    @endif
    </div>

    <div class="title-group mb-3 d-flex align-items-center">
        @if(auth()->user()->role == 0)
        <a class="text-muted me-2" style="color: rgb(95, 95, 240) !important; text-decoration: underline;" href="{{ route('pengajuan.show') }}">Pengajuan </a>
        @elseif(auth()->user()->role == 1)
        <a class="text-muted me-2" style="color: rgb(95, 95, 240) !important; text-decoration: underline;" href="{{ route('show.pengajuan') }}">Pengajuan </a>
        @endif
        <a class="text-muted me-2"> >> </a>
        <a class="text-muted" href="{{ route('detailpenyaluran') }}">Berkas Pengajuan</a>
    </div>
    
</div>

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
                    <div class="form-group">
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

@if(auth()->user()->role == 0)
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


<div class="row my-4">
    <div class="col-lg-5 col-12">
        <div class="custom-block custom-block-contact">
            <h6 class="mb-4">Detail data pengajuan</h6>

            <p>
                <strong>ID Ajuan:</strong>
                <a  class="ms-2">
                    {{$pengajuan->id_pengajuan}}
                </a>
            </p>

            <p>
                <strong>NOP:</strong>
                <a  class="ms-3">
                    {{$pengajuan->NOP}}
                </a>
            </p>
            <p>
                <strong>NIK:</strong>
                <a  class="ms-2">
                    {{$pengajuan->nik}}
                </a>
            </p>
            <p>
                <strong>Luasan:</strong>
                <a  class="ms-2">
                    {{$pengajuan->luasan}}
                </a>
            </p>
            <p>
                <strong>Komoditi:</strong>
                <a  class="ms-2">
                    {{$pengajuan->komoditi}}
                </a>
            </p>
            <p>
                <strong>Tahun:</strong>
                <a  class="ms-2">
                    {{$pengajuan->tahun}}
                </a>
            </p><p>
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
            @if(auth()->user()->role == 0)
            <a href="{{url('/pengajuan-show')}}" class="btn custom-btn1 ">
            @elseif(auth()->user()->role == 1)
            <a href="{{url('/show-pengajuan')}}" class="btn custom-btn1 ">
            @endif
                <i class="bi-arrow-up-left-circle-fill me-2"></i> Back 
            </a>
        </div>
    </div>
    <div class="col-lg-7 col-12">
        <div class="custom-block bg-white">
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

                    <form class="custom-form profile-form" method="POST" enctype="multipart/form-data">
                        @csrf  
                        @method('PUT')  
                        @if ($petani->ktp == 'Belum terisi')
                            <a href="{{ asset('images/profile/no-data-found.jpg') }}"  class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2" src="{{ asset('images/profile/no-data-found.jpg') }}" class="artist-image img-fluid">
                        @else
                            <a href="{{ asset('storage/assets/doc/ktp/' . $petani->ktp) }}" alt="{{ $petani->ktp }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2"src="{{ asset('storage/assets/doc/ktp/' . $petani->ktp) }}" alt="{{ asset('storage/assets/doc/ktp/' . $petani->ktp) }}" class="artist-image img-fluid">
                        @endif

                    </form>
                </div>

                <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab" tabindex="0">
                    <h6 class="mb-4">KK</h6>

                    <form class="custom-form profile-form"  method="POST" enctype="multipart/form-data">
                        @csrf  
                        @method('PUT')
                        @if ($petani->kk == 'Belum terisi')
                            <a href="{{ asset('images/profile/no-data-found.jpg') }}"  class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2" src="{{ asset('images/profile/no-data-found.jpg') }}" class="artist-image img-fluid">
                        @else
                            <a href="{{ asset('storage/assets/doc/kk/' . $petani->kk) }}" alt="{{ $petani->kk }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="ktp-image-wrap mb-2"src="{{ asset('storage/assets/doc/kk/' . $petani->kk) }}" alt="{{ $petani->kk }}" class="artist-image img-fluid">
                        @endif

                    </form>
                </div>

                <div class="tab-pane fade" id="notification-tab-pane" role="tabpanel" aria-labelledby="notification-tab" tabindex="0">
                    <h6 class="mb-4">SPPT</h6>

                    <form class="custom-form profile-form"  method="POST" enctype="multipart/form-data">
                        @csrf  
                        @method('PUT')  
                        <a href="{{ asset('storage/assets/doc/sppt/' . $lahan->Foto_SPPT) }}"" alt="{{ $lahan->Foto_SPPT }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                        <img class="sppt-image-wrap mb-2" src="{{ asset('storage/assets/doc/sppt/' . $lahan->Foto_SPPT) }}" alt="{{ $lahan->Foto_SPPT }}">

                    </form>
                </div>
            </div>
        </div>
    </div>

    
</div>

@endsection