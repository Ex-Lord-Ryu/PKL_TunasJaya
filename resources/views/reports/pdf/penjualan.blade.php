<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Penjualan Motor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .header h2 {
            margin: 10px 0;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
        .total-row {
            font-weight: bold;
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/logo.png') }}" alt="Logo">
        <h2>Laporan Penjualan Motor</h2>
        <p>Periode: {{ $periode }}</p>
    </div>

    <div class="info">
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i:s') }}</p>
        <p>Dicetak Oleh: {{ auth()->user()->name }}</p>
    </div>

    <table autosize="1">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 15%">Motor</th>
                <th style="width: 10%">Warna</th>
                <th style="width: 15%">No Rangka</th>
                <th style="width: 15%">No Mesin</th>
                <th style="width: 15%">Nama Pembeli</th>
                <th style="width: 15%">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($soldMotors as $index => $sold)
            @php $total += $sold->total_harga; @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sold->tanggal_penjualan->format('d-m-Y') }}</td>
                <td>{{ $sold->motor->nama_motor }}</td>
                <td>{{ $sold->warna->nama_warna }}</td>
                <td>{{ $sold->no_rangka }}</td>
                <td>{{ $sold->no_mesin }}</td>
                <td>{{ $sold->nama_pembeli }}</td>
                <td>Rp {{ number_format($sold->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="7" style="text-align: right">Total</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>{{ now()->format('d F Y') }}</p>
        <br><br><br>
        <p>( {{ auth()->user()->name }} )</p>
    </div>
</body>
</html> 