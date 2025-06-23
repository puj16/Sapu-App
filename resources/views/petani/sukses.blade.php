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

<div class="row my-4">
    <div class="col-lg-12 col-12">
        <div class="custom-block custom-block-balance-green">
            <small>Pembayaran Anda</small>

            <h2 class="mt-2 mb-3">BERHASIL</h2>

            <div class="d-flex">
                <div>
                    <small>NIK</small>
                    <p>{{ $penyaluran->nik }}</p>
                </div>
                <div class="ms-auto">
                    <small>Total Bayar</small>
                    <p class="text-end"> {{ number_format($penyaluran->total_bayar, 0, ',', '.') }}</p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection