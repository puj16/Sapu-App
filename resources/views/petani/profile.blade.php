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

    <div class="row my-4">
        <div class="col-lg-7 col-12">
            <div class="custom-block custom-block-profile bg-white">
                <h6 class="mb-4">Data Petani</h6>

                <p class="d-flex flex-wrap mb-2">
                    <strong>NIK:</strong>

                    <span>{{ $petani->nik }}</span>
                </p>

                <p class="d-flex flex-wrap mb-2">
                    <strong>Nama:</strong>

                    <span>{{ $petani->nama }}</span>
                </p>

                <p class="d-flex flex-wrap mb-2">
                    <strong>Nomor WA:</strong>

                    <span>{{ $petani->wa }}</span>
                </p>

                <p class="d-flex flex-wrap mb-2">
                    <strong>Foto KK:</strong>

                    <span class="artists-thumb">
                        @if ($petani->kk == 'Belum terisi')
                            <a href="images/profile/no-data-found.jpg"  class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="artists-image-wrap" src="images/profile/no-data-found.jpg" class="artists-image img-fluid">
                        @else
                            <a href="{{ asset('storage/assets/doc/kk/' . $petani->kk)}}" alt="{{ $petani->kk }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="artists-image-wrap"src="{{ asset('storage/assets/doc/kk/' . $petani->kk)}}" alt="{{ $petani->kk }}" class="fixed-image">
                        @endif
                    </span>
                </p>

                <p class="d-flex flex-wrap mb-2">
                    <strong>Foto KTP:</strong>

                    <span class="artists-thumb">
                        @if ($petani->ktp == 'Belum terisi')
                            <a href="images/profile/no-data-found.jpg"  class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="artists-image-wrap" src="images/profile/no-data-found.jpg" class="artists-image img-fluid">
                        @else
                            <a href="{{asset('storage/assets/doc/ktp/' .  $petani->ktp)}}" alt="{{ $petani->ktp }}" class="badge text-bg-success text-white mb-1">Lihat lebih lanjut</a>
                            <img class="artists-image-wrap"src="{{asset('storage/assets/doc/ktp/' .  $petani->ktp)}}" alt="{{ $petani->kk }}" class="fixed-image">
                        @endif
                    </span>
                </p>

                <div class="custom-form">
                    <button type="button" class="btn position-absolute text-white" style="bottom: 20px; right: 20px; background:#1D3557;" data-bs-toggle="modal" data-bs-target="#editModal{{$petani->nik}}" id="submit-button">
                        Ubah Data</button>
                </div>          
            </div>
        </div>
     </div>


<div class="edit">
    <div class="modal fade" id="editModal{{$petani->nik}}" tabindex="-1" aria-labelledby="editModalLabel{{$petani->nik}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down"> <!-- Tambahkan modal-fullscreen-sm-down -->
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Petani</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('petani.update', $petani->nik)}}" method="post" enctype="multipart/form-data">
                        @csrf  
                        @method('PUT')                                              
                        <div class="row">
                            <!-- Kolom kiri -->
                            <div class="col-md-6 col-12"> <!-- Agar penuh di mobile -->
                                <div class="mb-3">
                                    <label for="nik">NIK</label>
                                    <input type="text" class="form-control" name="nik" id="nik" value="{{$petani->nik }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="nama">Nama Petani</label>
                                    <input type="text" class="form-control" name="nama" id="nama" value="{{$petani->nama }}">
                                </div>
                                <div class="mb-3">
                                    <label for="wa">Nomor WA</label>
                                    <input type="text" class="form-control" name="wa" id="wa" value="{{$petani->wa }}">
                                </div>
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control" name="password" id="password">
                                    <div class="form-text text-danger">*Kosongi bila tidak ingin mengupdate password</div>
                                </div>
                            </div>

                            <!-- Kolom kanan -->
                            <div class="col-md-6 col-12"> <!-- Agar penuh di mobile -->
                                <div class="mb-3">
                                    <label for="kk">Foto KK</label>
                                    <input type="file" class="form-control" name="kk" accept="image/*">
                                    <div class="form-text text-danger">*Kosongi bila tidak ingin mengupdate kk</div>
                                </div>
                                <div class="mb-3">
                                    <label for="ktp">Foto KTP</label>
                                    <input type="file" class="form-control" name="ktp" accept="image/*">
                                    <div class="form-text text-danger">*Kosongi bila tidak ingin mengupdate ktp</div>
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="col-12 text-center mt-3"> <!-- Tombol di tengah di mobile -->
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

@endsection
@push('scripts')
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/custom.js"></script>

@endpush
