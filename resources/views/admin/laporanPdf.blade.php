<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Statistik dan Data Sekolah</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #0d9296;
        }

        .metrics-table {
            w-full: 100%;
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
    </style>
</head>

<body>

    <div class="header">
        <h1>LAPORAN RINGKASAN DATA SEKOLAH</h1>
        <p>SatuPeta Pendidikan Indonesia — Periode: Juli 2026</p>
    </div>

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

    <h3>Daftar Sekolah Terdaftar</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">NPSN</th>
                <th>Nama Sekolah</th>
                <th style="width: 10%">Jenjang</th>
                <th style="width: 15%">Kabupaten/Kota</th>
                <th style="width: 15%">No. Telp</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sekolahList as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->npsn }}</td>
                    <td>{{ $row->nama_sekolah }}</td>
                    <td>{{ strtoupper($row->jenjang) }}</td>
                    <td>{{ $row->kabupaten_kota }}</td>
                    <td>{{ $row->no_telepon ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
