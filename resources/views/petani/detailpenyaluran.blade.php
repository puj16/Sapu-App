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

<div class="row my-4">
    <div class="title-group mb-3 d-flex align-items-center">
        <div class="col-lg-7 col-md-4 col-sm-12 text-center text-md-start">
            <h1 class="h2 mb-0 ">Penyaluran {{ $tahun }}</h1>
        </div>
        <div class="d-flex justify-content-end col-lg-5 col-md-6 col-sm-12">
            <a class="btn custom-btn1" href="{{ route('penyaluran.show') }}">
                <i class="bi-arrow-up-left-circle-fill me-2"></i>
                Back
            </a>

            {{-- <form method="GET" action="{{ route('rdkk.index') }}" class="row g-2 d-flex justify-content-end">
                <!-- Dropdown Tahun -->
                <div class="">
                    <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($tahunPenyaluran->pluck('tahun')->unique()->sortDesc() as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </form> --}}
        </div>
    </div>
</div>

    <div class="row my-4">
        <div class="col-lg-12 col-12">
            <div class="custom-block custom-block-exchange">
            

                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <p>{{ $penyaluran->tahun}}/{{ $penyaluran->periode }}</p>
                            <h6>Komoditi {{ $penyaluran->komoditi }}</h6>
                        </div>
                    </div>

                    <div class="ms-auto ">
                        <small>Status Penyaluran</small>
                        <h6 class="text-end">
                            @switch((int) $penyaluran->status_penyaluran)
                                        @case(0)
                                            <span style="color: rgb(175, 175, 5)">Menunggu</span>
                                            @break
                                        @case(1)
                                            <span style="color: green">Berhasil</span>
                                            @break
                                        @default
                                           <span style="color: red">Gagal</span>
                            @endswitch

                        </h6>
                    </div>

                </div>
                @php
                    $ketersediaan= [];
                @endphp

                @foreach ($rdkk as $r) 
                    <div class="row align-items-center pb-2">
                        {{-- Nama Pupuk --}}
                        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                            <div>
                                <h6 class="mb-0"></h6>
                                <small>{{ $r->pupuk->nama_pupuk }}</small>
                            </div>
                        </div>

                        {{-- Volume MT 1,2,3 --}}
                        <div class="col-lg-6 col-12 mb-2 mb-lg-0">
                            <div class="d-flex justify-content-end gap-2 align-items-end">
                                <div class="text-center">
                                    <small>&nbsp;</small>
                                    <h6 class="mb-0">(</h6>
                                </div>
                                <div class="text-center">
                                    <small>MT-1</small>
                                    <h6 class="mb-0">{{ $r->volume_pupuk_mt1 ?? 0 }}</h6>
                                </div>
                                <div class="text-center">
                                    <small>&nbsp;</small>
                                    <h6 class="mb-0">+</h6>
                                </div>
                                <div class="text-center">
                                    <small>MT-2</small>
                                    <h6 class="mb-0">{{ $r->volume_pupuk_mt2 ?? 0 }}</h6>
                                </div>
                                <div class="text-center">
                                    <small>&nbsp;</small>
                                    <h6 class="mb-0">+</h6>
                                </div>
                                <div class="text-center">
                                    <small>MT-3</small>
                                    <h6 class="mb-0">{{ $r->volume_pupuk_mt3 ?? 0 }}</h6>
                                </div>
                                <div class="text-center">
                                    <small>&nbsp;</small>
                                    <h6 class="mb-0">)</h6>
                                </div>
                                <div class="text-center">
                                    <small>&nbsp;</small>
                                    <h6 class="ms-1 me-1 mb-0">X</h6>
                                </div>
                                <div class="text-center">
                                    <small>Harga Pupuk(Rp)</small>
                                    <h6 class="mb-0 text-end">{{ number_format($r->pupuk->harga, 0, ',', '.') }}</h6>
                                </div>
                                <div class="text-center">
                                    <small>&nbsp;</small>
                                    <h6 class="ms-1 me-1 mb-0">=</h6>
                                </div>
                            </div>
                        </div>
                        @foreach ($stok as $item)
                            @if ($item->id_pupuk == $r->id_pupuk)
                                @php
                                    $volPupuk = $r->volume_pupuk_mt1 + $r->volume_pupuk_mt2 + $r->volume_pupuk_mt3;
                                    if ($item->pupuk_tersisa < $volPupuk){
                                         $ketersediaan[] = "kurang";                                        
                                    }else {
                                        $ketersediaan[] = "cukup";
                                    };
                                @endphp
                            @endif
                            
                        @endforeach

                        {{-- Saldo / Total --}}
                        <div class="col-lg-2 col-12 text-end">
                            <div>
                                <small>Harga</small>
                                <h6>Rp {{ number_format($r->total_harga, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Total Harga --}}
                <div class="row align-items-center border-top pt-3 pb-2">
                    <div class="col-lg-10 col-12 mb-2 mb-lg-0">
                        <div class="text-lg-start text-center">
                            <small>Harga Total</small>
                        </div>
                    </div>

                    <div class="col-lg-2 col-12">
                        <div class="text-lg-end text-center">
                            <h6 class="price">Rp {{ number_format($penyaluran->total_bayar, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center border-top pt-3 ">
                    <div class="col-lg-6 col-12 mb-2 mb-lg-0">
                        <div class="text-lg-start text-center">
                            <p >
                                @switch((int) $penyaluran->status_pembayaran)
                                            @case(0)
                                                <span class="badge text-bg-danger pb-2 pt-2">Belum dibayar</span>
                                                @break
                                            @case(1)
                                                <span class="badge text-bg-success pb-2 pt-2">Berhasil dibayar</span>
                                                @break
                                            @default
                                                <span class="badge text-bg-edit pb-2 pt-2">Gagal dibayar</span>
                                @endswitch
                            </p>                        
                        </div>
                    </div>

                    @php
                         $adaYangKurang = in_array('kurang', $ketersediaan);
                    @endphp
                    <div class="col-lg-6 col-12">
                        <div class="d-flex text-lg-end text-end justify-content-end">
                            @switch((int) $penyaluran->status_penyaluran)
                                        @case(0)
                                            @switch((int) $penyaluran->status_pembayaran)
                                                @case(0)
                                                    @if ($adaYangKurang)
                                                    <button type="button" class="view-button me-1" disabled>
                                                        Stok Pupuk Kurang dari Kuota <i class="bi bi-arrow-right-circle-fill"></i>
                                                    </button>                              
                                                    <button class="view-button" data-bs-toggle="modal" data-bs-target="#paymentOfflineModal">Bayar Offline</button>
                                                    @else
                                                    <button type="button" class="view-button me-1" id="bayar-online-btn" data-bs-toggle="modal" data-bs-target="#paymentStepsModal">
                                                        Bayar online
                                                    </button>                              
                                                    <button class="view-button" data-bs-toggle="modal" data-bs-target="#paymentOfflineModal">Bayar Offline</button>
                                                    @endif
                                                    @break
                                                @case(1)
                                                    <button type="button" class="view-button me-1" disabled>
                                                        Bayar online
                                                    </button>                              
                                                    <button class="view-button" disabled>Bayar Offline</button>
                                                @break
                                                        @default
                                                        <button type="button" class="view-button me-1" id="bayar-online-btn" data-bs-toggle="modal" data-bs-target="#paymentStepsModal">
                                                            Bayar online
                                                        </button>                              
                                                        <button class="view-button" >Bayar Offline</button>
                                            @endswitch 
                                            @break
                                        @case(1)
                                            <span class="badge text-bg-edit pb-2 pt-2">Disalurkan Pada : {{ $penyaluran->tgl_penyaluran ? \Carbon\Carbon::parse($penyaluran->tgl_penyaluran)->format('d/m/Y') : '-'}}</span>
                                            @break
                                        @case(2)
                                            <span class="badge text-bg-edit pb-2 pt-2">Gagal disalurkan</span>
                                            <button class="view-button ms-1" data-bs-toggle="modal" data-bs-target="#gagalModal{{ $penyaluran->id }}">Bayar Offline</button>
                                        @break
                                        @default
                                            <span class="badge text-bg-edit pb-2 pt-2">Disalurkan Sebagian</span>
                                            <button class="view-button ms-1" data-bs-toggle="modal" data-bs-target="#infoModal{{ $penyaluran->id }}">Detail Penyaluran</button>
                            @endswitch  


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> 


<!-- Button trigger modal -->

  <!-- Modal -->
  <div class="modal fade" id="paymentOfflineModal" tabindex="-1" aria-labelledby="paymentStepsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-3 shadow">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="paymentStepsLabel">Tahapan Pembayaran</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <ol class="list-group list-group-numbered">
            <li class="list-group-item">
              Lihat nominal yang harus dibayarkan untuk tiap komoditi.
            </li>
            <li class="list-group-item">
                Temui petugas poktan, beritahukan komoditi dari pupuk yang akan dibayarkan.
            </li>
            <li class="list-group-item">
                Beritahukan NIK anda kepada petugas poktan.
            </li>
            <li class="list-group-item">
                Bayar pupuk, pupuk dalam 1 komoditi harus dibayarkan keseluruhan secara langsung
            </li>
            <li class="list-group-item">
                Terima pupuk yang sudah anda bayarkan
            </li>
          </ol>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="paymentStepsModal" tabindex="-1" aria-labelledby="paymentStepsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="paymentStepsLabel">Panduan Pembayaran</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <div class="mb-3">
                <h6>Detail Pembayaran</h6>
                <ul class="list-group">
                @foreach($rdkk as $r)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $r->pupuk->nama_pupuk }} - {{ ($r->volume_pupuk_mt1 + $r->volume_pupuk_mt2 + $r->volume_pupuk_mt3)}} Kg
                    <span>Total: Rp {{ number_format($r->total_harga, 0, ',', '.') }}</span>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light fw-bold">
                    Total Bayar:
                    <span>Rp {{ number_format($penyaluran->total_bayar, 0, ',', '.') }}</span>
                </li>
                </ul>
            </div>
    
            <div class="accordion" id="accordionPaymentSteps">
                @php
                $steps = [
                    'Klik tombol "Bayar Sekarang" untuk memulai proses pembayaran.' => 'Tombol ini akan membuka halaman pembayaran atau memunculkan metode pembayaran yang tersedia.',
                    'Pilih metode pembayaran seperti QRIS, Transfer Bank, atau E-Wallet.' => 'Setiap metode punya instruksi berbeda. Misalnya, QRIS akan menampilkan kode QR, sedangkan transfer akan memberikan nomor rekening.',
                    'Ikuti instruksi hingga selesai.' => 'Pastikan mengikuti instruksi dengan benar agar pembayaran tercatat.',
                    'Setelah pembayaran berhasil, status akan otomatis diperbarui.' => 'Sistem akan mendeteksi pembayaran Anda dalam beberapa menit.',
                    'Jika status tidak berubah, refresh halaman atau hubungi admin.' => 'Mungkin ada delay atau kendala teknis. Admin akan bantu cek status manual.'
                ];
                @endphp
    
                @foreach ($steps as $title => $detail)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->index }}">
                        {{ $loop->iteration }}. {{ $title }}
                    </button>
                    </h2>
                    <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionPaymentSteps">
                    <div class="accordion-body">
                        {{ $detail }}
                    </div>
                    </div>
                </div>
                @endforeach
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="pay-button">Bayar Sekarang</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
        </div>
    </div>
    
    <div class="modal fade" id="infoModal{{$penyaluran->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$penyaluran->id}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">Detail Pupuk yang disalurkan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @switch($penyaluran->status_penyaluran)
                        @case(0)
                        <i class="bi bi-clock-history icon-modal"></i>
                        <h5 class="mt-3">Pupuk belum disalurkan!</h5>   
                            @break
                        @case(1)
                        <i class="bi bi-clipboard-check icon-modal"></i>
                        <h5 class="mt-3">Pupuk berhasil disalurkan!</h5>
                            @break
                        @case(2)
                        <i class="bi bi-clipboard-x icon-modal"></i>
                        <h5 class="mt-3">Pupuk gagal disalurkan!</h5>
                        <p>Informasi menganai pupuk : {{ $penyaluran->info }}</p>
                            @break
                        @default
                        @foreach ($details as $detail) 
                            <li style="display: flex; justify-content: space-between; padding: 2px 0;">
                                <small style="min-width: 100px;">{{ $detail->pupuk->nama_pupuk }}</small>
                                <small style="text-align: right;">{{ $detail->volume_pupuk }}</small>
                            </li>
                        @endforeach
 
                    @endswitch
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{  env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){

        const modalElement = document.getElementById('paymentStepsModal');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }

      // SnapToken acquired from previous step
      snap.pay('{{ $penyaluran->snap_token }}', {
        // Optional
        onSuccess: function(result){
            window.location.href='{{ route('penyaluran.bayarsukses',['id'=>$penyaluran->id]) }}';
          /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
        },
        // Optional
        onPending: function(result){
          /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
        },
        // Optional
        onError: function(result){
          /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
        }
      });
    };
</script>  
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/custom.js"></script>

@endpush