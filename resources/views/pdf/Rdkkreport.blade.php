<!DOCTYPE html>
<html>
<head>
    <!-- CSS FILES -->      
    <title>Laporan RDKK {{ \Carbon\Carbon::now()->format('d-m-Y') }}</title>
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
    <h4>Alokasi Pupuk Bersubsidi {{ $tahun }}</h4>
    <p class="d-flex flex-wrap mb-4">
        <span class="me-2">Tahun:</span>
        <span>{{ $tahun}}</span>
        <br>
        <span class="me-2">Periode:</span>
        <span>{{ $periode}}</span>
        <br>
        <span class="me-2">Komoditi:</span>
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
                    <th scope="col" colspan="{{ $uniqueNamaPupuk->count() * 4 }}" style="border: 1px solid black;">Alokasi Pupuk Bersubsidi (Kg)</th>
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
                                <i class="bi-pencil-square me-1" data-bs-toggle="modal" data-bs-target="#editModal{{$firstItem->nik}}" style="cursor: pointer;"></i>
                                <i class="bi-trash3 me-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{$firstItem->nik}}" style="cursor: pointer;"></i>
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
                                <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt1 ?? "X" }}</td>
                                <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt2 ?? "X" }}</td>
                                <td style="border: 1px solid black;">{{ $rdkkItem->volume_pupuk_mt3 ?? "X" }}</td>
                                <td style="border: 1px solid black;">{{ 
                                    ($rdkkItem->volume_pupuk_mt1 ?? 0) + 
                                    ($rdkkItem->volume_pupuk_mt2 ?? 0) + 
                                    ($rdkkItem->volume_pupuk_mt3 ?? 0) 
                                }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                        <tr style="border: 2px solid black;">
                            <td colspan="3" style="border: 1px solid black;">Jumlah</td>
                            <td  style="border: 1px solid black;">{{ $luasan }}</td>
                            @foreach ($uniqueNamaPupuk as $pupukNama)
                                @php
                                    $mt1 = $dataRdkk->where('pupuk.nama_pupuk', $pupukNama)->sum('volume_pupuk_mt1');
                                    $mt2 = $dataRdkk->where('pupuk.nama_pupuk', $pupukNama)->sum('volume_pupuk_mt2');
                                    $mt3 = $dataRdkk->where('pupuk.nama_pupuk', $pupukNama)->sum('volume_pupuk_mt3');
                                    $volTotal = $mt1 +$mt2+$mt3;
                                @endphp
                            <td style="border: 1px solid black;">{{ $mt1}}</td>
                            <td style="border: 1px solid black;">{{ $mt2 }}</td>
                            <td style="border: 1px solid black;">{{ $mt3 }}</td>
                            <td style="border: 1px solid black;">{{ $volTotal }}</td>
                            @endforeach
                        </tr>
                </tbody>
        </table>
    </div>

</body>
</html>
