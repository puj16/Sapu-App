<!DOCTYPE html>
<html>
<head>
    <!-- CSS FILES -->      
    <title>Laporan Peminjaman E-Perpus {{ \Carbon\Carbon::now()->format('d-m-Y') }}</title>
    <style>
        /* Mengatur ukuran halaman dan margin untuk PDF */
        @page {
            size: A4;
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
        }

        /* Gaya CSS untuk mengatur tampilan */
        body {
            font-family: Arial, sans-serif;
            zoom: 80%; 
            margin: 0;
            padding: 0;
        }
        table{                
            border-collapse: collapse;
            border: 1px solid black;
            page-break-inside: auto;
        }
        th, td {
                font-size: 10px;
                padding: 4px 6px;
            }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .date {
            text-align: right;
            font-size: 9px;
            margin-top: 5px;
            margin-right: 5px;
        }
        .page-break {
            page-break-after: always;
        }
        .page-break:last-of-type {
            page-break-after: auto;
        }

        /* Menghindari pemotongan tabel saat dicetak */
        @media print {
            body {
                zoom: 100%;
            }

            table {
                width: 100%;
                table-layout: fixed;
                border-collapse: collapse;
                border: 1px solid black;
                page-break-inside: auto;
            }

            th, td {
                font-size: 9px;
                padding: 4px 6px;
                border-collapse: collapse;
                border: 1px solid black;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>

    <!-- Tanggal laporan yang digenerate menggunakan PHP -->

    <div class="date">
        Date: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
    </div>
    @if ($pengajuan->isEmpty())
    <div class="custom-block bg-white mt-3">
        <div class="d-flex justify-content-between align-items-center mb-0">
            <p class="mb-0 text-black">Data tidak tersedia</p>
        </div>
    </div>
@else

    @foreach ($pengajuan as $komoditi => $data) 
    <div class="page-break">
        <p class="d-flex flex-wrap mt-3 mb-4">
            <strong class="me-2">Laporan Pengajuan</strong>
            <br>
            <span>Tahun : {{ $tahun }}</span>
            <br>
            <span>Komoditi: {{ $komoditi }}</span>
        </p>

       
        <div class="table-responsive">
            <table class="account-table table" id="scheduleTable-{{ Str::slug($komoditi) }}">
                <thead>
                    <tr>
                        <th scope="col" style="border: 1px solid black; vertical-align: top;">No</th>
                        <th scope="col" style="border: 1px solid black; vertical-align: top;">ID</th>
                        <th scope="col" style="border: 1px solid black; vertical-align: top;">NOP</th>
                        <th scope="col" style="border: 1px solid black; vertical-align: top;">NIK</th>
                        <th scope="col" style="border: 1px solid black; vertical-align: top;">Luasan(Ha)</th>
                        <th scope="col" style="border: 1px solid black; vertical-align: top;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $pengajuan)
                        <tr>
                            <td scope="row" style="border: 1px solid black; vertical-align: top;">{{ $loop->iteration }}</td>
                            <td scope="row" style="border: 1px solid black; vertical-align: top;">{{ $pengajuan->id_pengajuan }}</td>
                            <td scope="row" style="border: 1px solid black; vertical-align: top;">{{ $pengajuan->NOP }}</td>
                            <td scope="row" style="border: 1px solid black; vertical-align: top;">{{ $pengajuan->nik}}</td>
                            <td scope="row" style="border: 1px solid black; vertical-align: top;">{{ $pengajuan->luasan }}</td>
                            <td scope="row" style="border: 1px solid black; vertical-align: top;">
                                @switch((int) $pengajuan->status_validasi)
                                    @case(0)
                                        <button >Menunggu </button>
                                        @break
                                    @case(1)
                                        <button > Berhasil</button>
                                        @break
                                    @default
                                        <button >Gagal</button>
                                @endswitch
                            </td>           
                            @php
                                $ktp = $petani->where('nik',$pengajuan->nik)->value('ktp');
                                $kk = $petani->where('nik',$pengajuan->nik)->value('kk');
                                $sppt = $lahan->where('NOP',$pengajuan->NOP)->value('Foto_SPPT')
                            @endphp                                                          

                            
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="page-break"></div>
    </div>
    @endforeach
@endif


    

</body>
</html>
