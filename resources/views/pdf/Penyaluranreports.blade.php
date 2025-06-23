<!DOCTYPE html>
<html>
<head>
    <!-- CSS FILES -->      
    <title>Laporan Detail Penyaluran {{ \Carbon\Carbon::now()->format('d-m-Y') }}</title>
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
    @if (request('status') == 2)
        <h4>Laporan Detail Penyaluran Gagal {{ $tahun }}</h4>
    @else
            <h4>Laporan Detail Penyaluran {{ $tahun }}</h4>
    @endif
    <p class="d-flex flex-wrap mb-4">
        <span class="me-2">Tahun:</span>
        <span>{{ $tahun}}</span>
        <br>
        <span class="me-2">Periode:</span>
        <span>{{ $periode}}</span>
        <br>
        <span class="me-2">Komoditi:</span>
        <span>{{ $komoditi}}</span>
        <br>
    </p>

    <div class="table-responsive">
        <table class="account-table table" id="scheduleTable">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">NIK</th>
                    <th scope="col">Pupuk - Volume</th>
                    <th scope="col">Total Bayar (Rp)</th>
                    <th scope="col">Tgl Bayar</th>
                    {{-- <th scope="col">Metode Bayar</th> --}}
                    <th scope="col">Status Pembayaran</th>
                    <th scope="col">Status Penyaluran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Penyaluran as $item)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $item->nik }}</td>
                    <td>
                        {{-- <li style="display: flex; justify-content: space-between; padding: 2px 0; ">
                            <b style="min-width: 100px;">Pupuk</b>
                            <b>-</b>
                            <b style="text-align: right;">Volume</b>
                        </li>                                                         --}}

                        @foreach ($rdkk as $r)
                            @if ($r->nik==$item->nik)
                                <li style="display: flex; justify-content: space-between; padding: 2px 0; border-bottom: 1px solid #000;">
                                    <small style="min-width: 100px;">{{ $r->pupuk->nama_pupuk }}</small>
                                    <small>-</small>
                                    <small style="text-align: right;">{{ ($r->volume_pupuk_mt1+$r->volume_pupuk_mt2+$r->volume_pupuk_mt3) }}</small>
                                </li>                                                        
                            @endif
                        @endforeach

                    </td>
                    <td>{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td>{{ $item->tgl_bayar ? \Carbon\Carbon::parse($item->tgl_bayar)->format('d/m/Y') : '-' }}</td>
                    {{-- <td>{{ $item->metode_bayar ?? '-' }}</td> --}}
                    <td>
                        @switch((int) $item->status_pembayaran)
                                    @case(0)
                                        <span class="badge text-bg-dark">Menunggu</span> <br>
                                        @break
                                    @case(1)
                                        <span class="badge text-bg-success">Berhasil</span> <br>
                                        <span>Metode Bayar : {{ $item->metode_bayar ?? '-' }}</span>
                                        @break
                                    @default
                                        <span class="badge text-bg-danger">Gagal</span>
                        @endswitch
                    </td>
                    <td>
                        @switch((int) $item->status_penyaluran)
                                    @case(0)
                                        <span class="badge text-bg-dark">Menunggu</span>
                                        @break
                                    @case(1)
                                        <span class="badge text-bg-success">Berhasil</span>
                                        @break
                                    @default
                                        <span class="badge text-bg-danger">Gagal</span>
                        @endswitch

                    </td>
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
