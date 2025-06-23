<!DOCTYPE html>
<html>
<head>
    <!-- CSS FILES -->      
    <title>Laporan Penyaluran {{ \Carbon\Carbon::now()->format('d-m-Y') }}</title>
    <style>
        /* Mengatur ukuran halaman dan margin untuk PDF */
        @page {
            size: A4;
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
            @bottom-right {
                content: "( " counter(page) " / " counter(pages) ")";
            }
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
        .pagenum:before {
            content: counter(page);
        }
        .date {
            text-align: right;
            font-size: 9px;
            margin-top: 5px;
            margin-right: 5px;
        }

        /* Menghindari pemotongan tabel saat dicetak */
        @media print {
            @page {
            size: A4;
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
            @bottom-right {
                content: "( " counter(page) " / " counter(pages) ")"; /* Total halaman ditampilkan di sini */
            }
        }
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
    
    <h4>Laporan Penyaluran {{ $tahun }}</h4>
    <p class="d-flex flex-wrap mb-4">
        <span class="me-2">Tahun:</span>
        <span>{{ $tahun}}</span>
        <br>
        <span class="me-2">Periode:</span>
        <span>{{ $periode}}</span>
        <br>
        
    </p>
    {{-- <div class="table-responsive">
        <table class="account-table table" id="scheduleTable">
            <thead>
                <tr>
                    <th scope="col" style="border: 1px solid black;">No</th>
                    <th scope="col" style="border: 1px solid black;">Komoditi</th>
                    <th scope="col" style="border: 1px solid black;">Status Penyaluran</th>
                    <th scope="col" style="border: 1px solid black;">Nama Pupuk </th>
                    <th scope="col" style="border: 1px solid black;">Volume Total</th>
                    <th scope="col" style="border: 1px solid black;">Harga per KG</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Penyaluran as $komoditi => $data)
                @php
                    $firstItem = $data->first(); // Ambil item pertama dalam grup
                    $pupukIds = $rdkkData->where('komoditi', $firstItem->komoditi)
                                         ->pluck('id_pupuk')
                                         ->unique();
                    $jumlahPupuk = $pupukIds->count(); // Hitung jumlah pupuk yang unik
                @endphp
                @foreach ($pupukIds as $index => $item)
                @php
                    $pupuk = \App\Models\Pupuk::findOrFail($item);
                    $volume = $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt1') 
                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt2') 
                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt3');
                @endphp
                <tr>
                    @if ($index === 0)
                        <td rowspan="{{$jumlahPupuk }}" style="border: 1px solid black;">{{ $loop->parent->iteration }}</td>
                        <td rowspan="{{$jumlahPupuk }}" style="border: 1px solid black;">{{ $firstItem->komoditi }}</td>
                        <td rowspan="{{$jumlahPupuk }}" style="border: 1px solid black;">{{ $statusPenyaluran[$komoditi] ?? '-' }}</td>
                    @endif
                    <td style="border: 1px solid black;">{{ $pupuk->nama_pupuk }}</td>
                    <td style="border: 1px solid black;">{{ $volume }} Kg</td>
                    <td style="border: 1px solid black;">Rp{{ number_format($pupuk->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div> --}}

    <div class="table-responsive">
        <table class="account-table table" id="scheduleTable">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Komoditi</th>
                    <th scope="col">Status Penyaluran</th>
                    <th scope="col" >Pupuk <br> <span class="ms-4 me-0">Tersalurkan/Total (Kg)</span></th>
                    <th scope="col">Terbayar / Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Penyaluran as $komoditi=>$data)
                @php
                    $firstItem = $data->first(); // Ambil item pertama dalam grup
                @endphp
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $firstItem->komoditi }}</td>
                    <td>{{ $statusPenyaluran[$komoditi] ?? '-' }}

                        </td>
                    <td>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @php
                                $pupukIds = $rdkkData->where('komoditi', $firstItem->komoditi)
                                                    ->pluck('id_pupuk')
                                                    ->unique();
                            @endphp
                    
                            @foreach ($pupukIds as $item)
                            @php
                                $pupuk = \App\Models\Pupuk::findOrFail($item);
                                $volume = $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt1') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt2') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt3');
                                $niktersalur = $data->where('status_penyaluran',1)->pluck('nik') ->unique();
                                $voltersalur =0;
                                foreach ($niktersalur as $n) {
                                    $volpernik= $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt1') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt2') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt3');
                                            $voltersalur +=$volpernik;
                                }
                            @endphp
                                <li style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; padding: 2px 0;">
                                    <p style="margin: 0;">{{ $pupuk->nama_pupuk }}</p>
                                    <p style="margin: 0;">{{ $voltersalur }} / {{ $volume }} </p>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td> 
                        @php
                            $totalterbayar = $data->where('status_pembayaran',1)->sum('total_bayar');
                            $totalbayar = $data->sum('total_bayar');
                        @endphp
                        <b>Rp{{ number_format($totalterbayar, 0, ',', '.') }} / Rp{{ number_format($totalbayar, 0, ',', '.') }}</b>                                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<footer>
    <div style="position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px;">
        {{-- <span class="pagecurr"></span>
        <span>/</span> --}}
        <span class="pagenum"></span>

    </div>
</footer>

</body>
</html>
