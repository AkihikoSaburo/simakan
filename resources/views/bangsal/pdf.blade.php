<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Permintaan Makanan - {{ $order->bangsal->nama_bangsal }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #334155;
            padding-bottom: 10px;
        }

        .header-table td {
            vertical-align: top;
            padding: 4px 0;
        }

        .title {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 9pt;
            color: #64748b;
            margin-top: 2px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 3px 0;
            font-size: 9pt;
        }

        .meta-label {
            font-weight: bold;
            color: #475569;
            width: 100px;
        }

        .meta-separator {
            width: 15px;
            text-align: center;
            color: #64748b;
        }

        .meta-value {
            color: #0f172a;
            font-weight: 500;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #94a3b8;
            padding: 7px 9px;
            font-size: 8.5pt;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .text-center {
            text-align: center !important;
        }

        .badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
            margin-bottom: 2px;
            border: 1px solid #cbd5e1;
        }

        .rekap-section {
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .rekap-title {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 6px;
            border-bottom: 1.5px solid #475569;
            padding-bottom: 3px;
            text-transform: uppercase;
        }

        .rekap-table {
            width: 100%;
            border-collapse: collapse;
        }

        .rekap-table th,
        .rekap-table td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
            font-size: 8.5pt;
        }

        .rekap-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }

        .rekap-table td {
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
            color: #0f172a;
        }

        .footer-sig {
            margin-top: 40px;
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .footer-sig td {
            width: 50%;
            font-size: 9pt;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <!-- Header Instansi & Dokumen -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="title">SIMAKAN</div>
                <div class="subtitle">Sistem Informasi Permintaan Makanan Pasien</div>
                <div class="subtitle">Rumah Sakit Umum Daerah Andi Makassau</div>
            </td>
            <td style="width: 40%;">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">ID Form</td>
                        <td class="meta-separator">:</td>
                        <td class="meta-value">#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Bangsal</td>
                        <td class="meta-separator">:</td>
                        <td class="meta-value">{{ $order->bangsal->nama_bangsal }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Tanggal</td>
                        <td class="meta-separator">:</td>
                        <td class="meta-value">{{ $order->tanggal_pesanan->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Petugas</td>
                        <td class="meta-separator">:</td>
                        <td class="meta-value">{{ $order->creator->username ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- Tabel Data Permintaan Pasien -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Pasien</th>
                <th style="width: 12%;">No. RM</th>
                <th style="width: 13%;">Kamar / Kelas</th>
                <th style="width: 20%;">Bentuk Makanan</th>
                <th style="width: 12%;">Jenis Diet</th>
                <th style="width: 13%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($order->orderDetails as $detail)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td style="font-weight: bold;">{{ $detail->patient->nama ?? 'Tanpa Nama' }}</td>
                    <td style="font-family: monospace;">{{ $detail->patient->no_rm ?? '-' }}</td>
                    <td>{{ $detail->patient->kamar ?? '-' }}</td>
                    <td>
                        @php
                            $makanan = [];
                            if ($detail->nasi)
                                $makanan[] = 'Nasi';
                            if ($detail->bubur)
                                $makanan[] = 'Bubur';
                            if ($detail->makanan_cair)
                                $makanan[] = 'Msk. Cair / Susu';
                            if ($detail->bs)
                                $makanan[] = 'Bubur Saring';
                            if ($detail->sonde)
                                $makanan[] = 'Sonde';
                        @endphp

                        @forelse($makanan as $item)
                            <span class="badge">{{ $item }}</span>
                        @empty
                            <span style="font-style: italic; color: #64748b;">Belum memilih</span>
                        @endforelse
                    </td>
                    <td>{{ $detail->diet_pasien ?? '-' }}</td>
                    <td style="font-style: italic; color: #475569; font-size: 8pt;">
                        {{ $detail->keterangan ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; font-style: italic; color: #64748b;">
                        Belum ada data detail pasien.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Rekapitulasi Porsi -->
    <div class="rekap-section">
        <div class="rekap-title">Rekapitulasi Porsi Form</div>
        <table class="rekap-table">
            <thead>
                <tr>
                    <th>Nasi</th>
                    <th>Bubur</th>
                    <th>Msk. Cair / Susu</th>
                    <th>Bubur Saring</th>
                    <th>Sonde</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->nasi_count }}</td>
                    <td>{{ $order->bubur_count }}</td>
                    <td>{{ $order->makanan_cair_count }}</td>
                    <td>{{ $order->bs_count }}</td>
                    <td>{{ $order->sonde_count }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Tanda Tangan/Persetujuan -->
    <table class="footer-sig">
        <tr>
            <td style="text-align: left;">
                <p style="margin-bottom: 50px;">Petugas Pengirim,</p>
                <p style="font-weight: bold; text-decoration: underline;">
                    {{ $order->creator->username ?? '........................' }}</p>
                <p style="font-size: 8pt; color: #64748b;">Staf Bangsal {{ $order->bangsal->nama_bangsal }}</p>
            </td>
            <td style="text-align: right; padding-right: 20px;">
                <p style="margin-bottom: 50px;">Petugas Dapur Gizi,</p>
                <p style="font-weight: bold; text-decoration: underline;">........................................</p>
                <p style="font-size: 8pt; color: #64748b;">Penerima</p>
            </td>
        </tr>
    </table>
</body>

</html>