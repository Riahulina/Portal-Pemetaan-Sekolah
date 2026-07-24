<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Demografi Sekolah</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .metrics-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .metrics-table td {
            width: 25%;
            padding: 15px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            text-align: center;
        }

        .metrics-title {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            font-weight: bold;
        }

        .metrics-value {
            font-size: 18px;
            font-weight: bold;
            color: #222;
            margin-top: 5px;
            display: block;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #0d9296;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .letterhead {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .letterhead tr {
            border-bottom: 2px solid #333;
        }

        .letterhead td {
            vertical-align: middle;
            padding: 10px 0;
        }

        .letterhead .logo-cell {
            width: 20%;
            text-align: left;
        }

        .letterhead .logo-cell img {
            width: 220px;
            height: auto;
        }

        .letterhead .title-cell {
            width: 60%;
            text-align: center;
        }

        .letterhead .title-cell h1 {
            margin: 0;
            font-size: 26px;
            color: #0d9296;
            line-height: 1.3;
        }

        .letterhead .title-cell p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }

        .letterhead .spacer-cell {
            width: 20%;
        }
    </style>
</head>

<body>

    <table class="letterhead">
        <tr>
            <td class="logo-cell">
                @if(!empty($logoTempPath) && file_exists($logoTempPath))
                    <img src="file://{{ $logoTempPath }}" alt="SatuPeta Logo" width="220" height="82">
                @endif
            </td>
            <td class="title-cell">
                <h1>LAPORAN REKAPITULASI DEMOGRAFI SEKOLAH</h1>
                <p>SatuPeta Peta Pendidikan Indonesia — Periode: {{ $periode }}</p>
            </td>
            <td class="spacer-cell"></td>
        </tr>
    </table>

    <!-- Ringkasan Ringkas Kotak Atas -->
    <table class="metrics-table">
        <tr>
            <td>
                <span class="metrics-title">Total Sekolah</span>
                <span class="metrics-value">{{ number_format($totalSekolah, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="metrics-title">Menunggu Verifikasi</span>
                <span class="metrics-value">{{ number_format($menungguVerifikasi, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="metrics-title">Disetujui</span>
                <span class="metrics-value">{{ number_format($disetujui, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="metrics-title">Ditolak</span>
                <span class="metrics-value">{{ number_format($ditolak, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <h3>Rekapitulasi Data Sekolah per Wilayah</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Provinsi</th>
                <th style="width: 30%">Kabupaten/Kota</th>
                <th style="width: 20%">Total Sekolah</th>
                <th style="width: 20%">Total Siswa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekapWilayah as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->provinsi ?? '-' }}</td>
                    <td>{{ $row->kabupaten_kota ?? '-' }}</td>
                    <td>{{ number_format($row->total_sekolah, 0, ',', '.') }}</td>
                    <td>{{ number_format($row->total_siswa ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
