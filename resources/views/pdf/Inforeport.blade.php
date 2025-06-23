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
            .table-stok-wrapper {
                width: 100%;
                display: flex;
                justify-content: flex-end; /* nempel ke kanan */
                margin-top: 10px;
                margin-bottom: 20px; /* spasi bawah */
            }

            .table-stok {
                width: auto;
                min-width: 300px;
                table-layout: auto;
                white-space: nowrap;
                border: 1px solid black;
            }


        }
    </style>
</head>
<body>

    <!-- Tanggal laporan yang digenerate menggunakan PHP -->

    <div class="date">
        Date: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
    </div>
    
@if ($table ==1)
        <div class="d-flex">
            <h6 class="mb-4 text-align-center">Pupuk Terbayar
                @if ($start==0&&$end==0)
                ({{ str_pad($bulanFilter, 2, '0', STR_PAD_LEFT) }} - {{ $tahunFilter }}) 
                @else
                {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}   
                @endif
    
            </h6>
        </div>
        <div class="table-responsive">
            <table class="account-table table table-bordered" id="spembayaranTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Komoditi</th>
                        <th scope="col">Status Penyaluran</th>
                        <th scope="col">NIK Penerima</th>
                        <th scope="col">Terbayar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($filter_pembayaran as $komoditi=>$pembayaran)
                    @php
                        $firstItem = $pembayaran->first(); // Ambil item pertama dalam grup
                        $jmlhNIK = $pembayaran->pluck('nik')->unique()->count();
                        $nikList = $pembayaran->pluck('nik')->unique();
                        $totalterbayar = $pembayaran->where('status_pembayaran',1)->sum('total_bayar');
                    @endphp
                    @foreach ($nikList as $index => $nik)
                        <tr style="background: white">
                            @if ($index === 0)
                                <td scope="row" rowspan="{{ $jmlhNIK }}">{{ $counter}}</td>
                                <td rowspan="{{ $jmlhNIK }}">{{ $firstItem->komoditi }}</td>
                            @endif
                            @php
                                $terbayarItem = $pembayaran->where('nik', $nik)->first(); // Ambil elemen pertama dari koleksi
                            @endphp

                            <td>{{ $terbayarItem->tgl_bayar }}</td>
                            <td>{{ $nik }}</td>
                            <td>                                                        
                                @if ($terbayarItem && $terbayarItem->status_pembayaran == 1)
                                    {{ number_format($terbayarItem->total_bayar, 0, ',', '.') }}
                                @else
                                    -
                                @endif                                            
                            </td>
                        </tr>
                    @endforeach 
                    @php
                        $counter++; // Tingkatkan counter setelah setiap grup selesai
                    @endphp

                    <tr style="background:#ececec">
                        <td colspan="4">Total</td>
                        <td>Rp{{ number_format($totalterbayar, 0, ',', '.') }}  </td>
                    </tr>
                    @foreach ($Penyaluran as $komoditi=>$detail)
                            @if ($komoditi == $firstItem->komoditi)
                                @php
                                    $totals = $detail->sum('total_bayar');
                                    $belumterbayar = $totals - ($detail->where('status_pembayaran',1)->sum('total_bayar'));
                                @endphp
                            @endif
                    @endforeach
                    <tr style="background: #ececec">
                        <td colspan="4">Total Belum Terbayar</td>
                        
                        <td>Rp{{ number_format($belumterbayar, 0, ',', '.') }}  </td>

                    </tr>
                    <tr style="background: #ececec">
                        <td colspan="4">Total Keseluruhan</td>
                        
                        <td>Rp{{ number_format($totals, 0, ',', '.') }}  </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
@elseif($table ==2)
        <div class="d-flex">
            <h6 class="mb-4 text-align-center">Pupuk Tersalurkan 
            @if ($start==0&&$end==0)
            ({{ str_pad($bulanFilter, 2, '0', STR_PAD_LEFT) }} - {{ $tahunFilter }}) 
            @else
            {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}   
            @endif
            </h6>
        </div>
        <div class="table-responsive">
            <table class="account-table table table-bordered" id="spembayaranTable">
                <thead>
                    @php
                        $pupukIds = $rdkkData->pluck('id_pupuk')
                                        ->unique();
                    @endphp
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Komoditi</th>
                        <th scope="col">Tanggal Penyaluran</th>
                        <th scope="col">NIK Penerima</th>
                        @foreach ($pupukIds as $item)
                            @php
                                $pupuk = \App\Models\Pupuk::findOrFail($item);
                            @endphp
                        <th scope="col">{{ $pupuk->nama_pupuk }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($filter_penyaluran as $komoditi=>$penyaluran)
                    @php
                        $firstItem = $penyaluran->first(); // Ambil item pertama dalam grup
                        $jmlhNIK = $penyaluran->pluck('nik')->unique()->count();
                        $nikList = $penyaluran->pluck('nik')->unique();
                        $totaltersalurkan = $penyaluran->where('status_penyaluran',1)->count('nik');

                        
                    @endphp
                    @foreach ($nikList as $index => $nik)
                        <tr>
                            @if ($index === 0)
                                <td scope="row" rowspan="{{ $jmlhNIK }}">{{ $counter}}</td>
                                <td rowspan="{{ $jmlhNIK }}">{{ $firstItem->komoditi }}</td>
                            @endif
                            @php
                                $tersalurkanItem = $penyaluran->where('nik', $nik)->first(); // Ambil elemen pertama dari koleksi
                            @endphp

                            <td>{{ $tersalurkanItem->tgl_penyaluran }}</td>
                            <td>{{ $nik }}</td>
                            @foreach ($pupukIds as $item)
                            @php
                                $volindividu = $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$nik)->sum('volume_pupuk_mt1')
                                                + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$nik)->sum('volume_pupuk_mt2')
                                                +$rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$nik)->sum('volume_pupuk_mt3');
                            @endphp

                            <td>{{ $volindividu }}</td>
                            @endforeach
                        </tr>
                    @endforeach 
                    @php
                        $counter++; // Tingkatkan counter setelah setiap grup selesai
                    @endphp

                    <tr style="background: #ececec">
                        <td colspan="4">Total </td>
                        @foreach ($pupukIds as $item)
                            @php
                            $niktersalur = $penyaluran->where('status_penyaluran',1)->pluck('nik') ->unique();
                                $voltersalur =0;
                                foreach ($niktersalur as $n) {
                                    $volpernik= $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt1') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt2') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt3');
                                            $voltersalur +=$volpernik;
                                }
                            @endphp

                            <td>{{ $voltersalur}}</td>
                        @endforeach
                    </tr>
                                                                    <tr style="background: #ececec">
                                                    <td colspan="4">Total Tersalurkan</td>
                                                    @foreach ($pupukIds as $item)
                                                        @php
                                                        
                                                           $niktersalur = App\Models\Penyaluran::where('komoditi', $firstItem->komoditi)->where('status_penyaluran',1)->pluck('nik') ->unique();
                                                        //    dd($niktersalur);
                                                            $voltersalurkan =0;
                                                            foreach ($niktersalur as $n) {
                                                                $volpernik= $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt1') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt2') 
                                                                        + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->where('nik',$n)->sum('volume_pupuk_mt3');
                                                                        $voltersalurkan +=$volpernik;
                                                            }
                                                        @endphp
        
                                                        <td>{{ $voltersalurkan}}</td>
                                                    @endforeach
                                                </tr>

                    <tr style="background: #ececec">
                        <td colspan="4">Total Keseluruhan Penerima Penyaluran</td>
                        @foreach ($pupukIds as $item)
                            @php
                                $volume = $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt1') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt2') 
                                            + $rdkkData->where('komoditi', $firstItem->komoditi)->where('id_pupuk', $item)->sum('volume_pupuk_mt3');
                            
                            @endphp
                            <td>{{ $volume }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
@elseif (3)
        <div class="d-flex">
            <h6 class="mb-4 text-align-center">Pupuk Tersalurkan Sebagian
            @if ($start==0&&$end==0)
            ({{ str_pad($bulanFilter, 2, '0', STR_PAD_LEFT) }} - {{ $tahunFilter }}) 
            @else
            {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}   
            @endif
            </h6>
        </div>
<div class="table-stok-wrapper">
    <table class="table-stok">
        <thead>
            <tr>
                <th>Nama Pupuk</th>
                <th>Stok Datang</th>
                <th>Stok Tersisa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stok as $item)
                <tr>
                    <td>{{ $item->pupuk->nama_pupuk }}</td>
                    <td>{{ $item->pupuk_datang }}</td>
                    <td>{{ $item->pupuk_tersisa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
        <div class="table-responsive">
            <table class="account-table table table-bordered">
                <thead>
                    @php
                        $pupukIds = $rdkkData->pluck('id_pupuk')
                                            ->unique();

                    @endphp
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Komoditi</th>
                        <th scope="col">Tanggal Penyaluran</th>
                        <th scope="col">NIK Penerima</th>
                        @foreach ($pupukIds as $item)
                            @php
                                $pupuk = \App\Models\Pupuk::findOrFail($item);
                            @endphp
                        <th scope="col">{{ $pupuk->nama_pupuk }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailPenyaluranSebagian as $id_penyaluran => $data)
                    @php
                        $firstItem = $data->first(); // Ambil item pertama dalam grup
                    @endphp
                    <tr>
                        <td>{{$loop->iteration }}</td>
                        <td>
                            @php
                            $record = $penyaluranid->firstWhere('id', $firstItem->id_penyaluran);
                            $komoditi = $record?->komoditi;
                            @endphp
                            {{ $record?->komoditi;}}
                        </td>
                        <td>{{ $firstItem->created_at }}</td>
                        <td>{{ $record?->nik }}</td>
                        @foreach ($pupukIds as $id)
                            @php
                                $detail = $data->firstWhere('id_pupuk', $id);
                            @endphp
                            <td>{{ $detail?->volume_pupuk ?? 0 }}</td>
                        @endforeach
                    </tr>                                                    
                    @endforeach

                </tbody>
            </table>
        </div>
@endif







<footer>
    <div style="position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px;">
        {{-- <span class="pagecurr"></span>
        <span>/</span> --}}
        <span class="pagenum"></span>

    </div>
</footer>

</body>
</html>
