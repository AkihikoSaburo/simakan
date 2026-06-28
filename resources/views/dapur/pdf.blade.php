<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Permintaan Makanan Pasien - {{ $carbonDate->format('d-m-Y') }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #000000;
            margin: 0;
            padding: 0;
        }

        .sheet {
            position: relative;
            min-height: 23cm;
            padding-bottom: 140px;
            box-sizing: border-box;
        }


        /* Kop Surat Minimalis Hitam Putih */
        .header-container {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-bottom: 2px solid #000000;
            padding-bottom: 8px;
        }

        .header-container td {
            vertical-align: top;
            border: none;
            padding: 0;
            background: transparent !important;
        }

        .hospital-name {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
        }

        .hospital-sub {
            font-size: 8.5pt;
            color: #000000;
            margin-top: 3px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-info {
            text-align: right;
            font-size: 9pt;
            color: #000000;
        }

        /* Judul Laporan */
        .title-section {
            text-align: center;
            margin: 12px 0;
        }

        .title-main {
            font-size: 11pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tabel Data Pasien Model Excel (Rapih, Gridline Jelas) */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000000;
            padding: 6px 4px;
            font-size: 8pt;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .data-table th {
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            background-color: #ffffff;
            color: #000000;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .font-bold {
            font-weight: bold;
        }

        .checkmark {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            color: #000000;
            font-weight: bold;
        }

        /* Kontainer Bawah di Posisi Paling Bawah Kertas */
        .bottom-container {
            position: absolute;
            bottom: 15px;
            left: 0px;
            right: 0px;
            width: 100%;
            height: 145px;
        }

        /* Summary Box Minimalis (Seperti Excel) */
        .summary-box {
            width: 45%;
            float: left;
        }

        .summary-title {
            font-size: 9pt;
            font-weight: bold;
            color: #000000;
            margin: 0 0 6px 0;
            border-bottom: 1.5px solid #000000;
            padding-bottom: 3px;
            text-transform: uppercase;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .summary-table td {
            padding: 3px 0;
            color: #000000;
            border: none !important;
        }

        .summary-value {
            text-align: right;
            font-weight: bold;
            color: #000000;
        }

        /* Tanda Tangan */
        .signature-box {
            width: 40%;
            float: right;
            text-align: center;
            margin-top: 35px;
        }

        .signature-title {
            font-size: 9pt;
            color: #000000;
            margin: 0 0 45px 0;
        }

        .signature-name {
            font-size: 9pt;
            font-weight: bold;
            color: #000000;
            text-decoration: underline;
            margin: 0;
        }

        .signature-sub {
            font-size: 8pt;
            color: #000000;
            margin: 2px 0 0 0;
        }

        .clear-fix {
            clear: both;
        }
    </style>
</head>

<body>
    @foreach($orders as $index => $order)
        @php
            $nasiCount = $order->orderDetails->where('nasi', true)->count();
            $buburCount = $order->orderDetails->where('bubur', true)->count();
            $cairCount = $order->orderDetails->where('makanan_cair', true)->count();
            $bsCount = $order->orderDetails->where('bs', true)->count();
            $sondeCount = $order->orderDetails->where('sonde', true)->count();
            
            $puasaCount = $order->orderDetails->filter(fn($detail) => 
                !$detail->nasi && 
                !$detail->bubur && 
                !$detail->makanan_cair && 
                !$detail->bs && 
                !$detail->sonde
            )->count();
        @endphp

        <div class="sheet" @if(!$loop->last) style="page-break-after: always;" @endif>
            <!-- Kop Surat Minimalis Hitam Putih -->
            <table class="header-container">
                <tr>
                    <td>
                        <div class="hospital-name">{{ \App\Models\Setting::get('nama_rumah_sakit', 'RSUD Andi Makkasau') }}</div>
                        <div class="hospital-sub">Instalasi Gizi </div>
                    </td>
                    <td class="meta-info" style="width: 45%;">
                        <div style="margin-bottom: 2px;">Ruangan / Bangsal: <strong>{{ $order->bangsal->nama_bangsal }}</strong></div>
                        <div>Tanggal: <strong>{{ $order->tanggal_pesanan->format('d / m / Y') }}</strong></div>
                    </td>
                </tr>
            </table>

            <!-- Judul Laporan -->
            <div class="title-section">
                <div class="title-main">Form Permintaan Makanan Pasien</div>
            </div>

            <!-- Tabel Data Pasien Bertema Excel -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 4%;">NO</th>
                        <th rowspan="2" style="width: 10%;">KELAS</th>
                        <th rowspan="2" style="width: 25%;">NAMA PASIEN</th>
                        <th rowspan="2" style="width: 10%;">NO RM</th>
                        <th rowspan="2" style="width: 12%;">TANGGAL LAHIR</th>
                        <th colspan="5" style="width: 25%;">BENTUK MAKANAN</th>
                        <th rowspan="2" style="width: 14%;">DIET PASIEN</th>
                        <th rowspan="2" style="width: 10%;">KET</th>
                    </tr>
                    <tr>
                        <th style="width: 5%;">NASI</th>
                        <th style="width: 5%;">BUBUR</th>
                        <th style="width: 5%;">CAIR</th>
                        <th style="width: 5%;">BS</th>
                        <th style="width: 5%;">SONDE</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($order->orderDetails as $detail)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center">{{ $detail->patient->kamar ?? '-' }}</td>
                            <td class="font-bold">{{ $detail->patient->nama ?? 'Tanpa Nama' }}</td>
                            <td class="text-center" style="font-family: monospace;">{{ $detail->patient->no_rm ?? '-' }}</td>
                            <td class="text-center">
                                {{ $detail->patient->tanggal_lahir ? \Carbon\Carbon::parse($detail->patient->tanggal_lahir)->format('d/m/Y') : '-' }}
                            </td>
                            <!-- Bentuk Makanan Checkmarks -->
                            <td class="text-center checkmark">{!! $detail->nasi ? '✔' : '' !!}</td>
                            <td class="text-center checkmark">{!! $detail->bubur ? '✔' : '' !!}</td>
                            <td class="text-center checkmark">{!! $detail->makanan_cair ? '✔' : '' !!}</td>
                            <td class="text-center checkmark">{!! $detail->bs ? '✔' : '' !!}</td>
                            <td class="text-center checkmark">{!! $detail->sonde ? '✔' : '' !!}</td>
                            <!-- Diet & Keterangan -->
                            <td>{{ $detail->diet_pasien ?? '-' }}</td>
                            <td style="font-style: italic; font-size: 7.5pt;">{{ $detail->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center" style="padding: 25px; font-style: italic;">
                                Belum ada data permintaan pasien untuk bangsal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Bottom Section (Summary & Tanda Tangan) diposisikan di paling bawah halaman -->
            <div class="bottom-container">
                <!-- Summary Card bertema Excel -->
                <div class="summary-box">
                    <div class="summary-title">Pasien Hari Ini</div>
                    <table class="summary-table">
                        <tr>
                            <td>Makanan Biasa (Nasi)</td>
                            <td class="summary-value">{{ $nasiCount }} Orang</td>
                        </tr>
                        <tr>
                            <td>Makanan Lunak (Bubur)</td>
                            <td class="summary-value">{{ $buburCount }} Orang</td>
                        </tr>
                        <tr>
                            <td>Makanan Cair</td>
                            <td class="summary-value">{{ $cairCount }} Orang</td>
                        </tr>
                        <tr>
                            <td>Bubur Saring (BS)</td>
                            <td class="summary-value">{{ $bsCount }} Orang</td>
                        </tr>
                        <tr>
                            <td>Pasien Sonde</td>
                            <td class="summary-value">{{ $sondeCount }} Orang</td>
                        </tr>
                        <tr style="border-top: 1px dashed #000000;">
                            <td style="font-weight: bold; padding-top: 4px;">Puasa</td>
                            <td class="summary-value" style="color: #000000; padding-top: 4px;">{{ $puasaCount }} Orang</td>
                        </tr>
                    </table>
                </div>

                <!-- Signature Box -->
                <div class="signature-box">
                    <p class="signature-title">Yang Meminta,</p>
                    <p class="signature-name">{{ $order->creator->username ?? 'Staf Ruangan' }}</p>
                    <p class="signature-sub">Petugas Ruangan / Bangsal</p>
                </div>

                <div class="clear-fix"></div>
            </div>
        </div>
    @endforeach
</body>

</html>
