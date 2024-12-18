<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Stock Motor</title>
    <style>
        /* Gunakan style yang sama dengan penjualan.blade.php */
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/logo.png') }}" alt="Logo">
        <h2>Laporan Stock Motor</h2>
        <p>Status: {{ $status }}</p>
    </div>

    <div class="info">
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i:s') }}</p>
        <p>Dicetak Oleh: {{ auth()->user()->name }}</p>
    </div>

    <table autosize="1">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Motor</th>
                <th style="width: 10%">Warna</th>
                <th style="width: 15%">No Rangka</th>
                <th style="width: 15%">No Mesin</th>
                <th style="width: 10%">Status</th>
                <th style="width: 15%">Harga Beli</th>
                <th style="width: 15%">Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalBeli = 0;
                $totalJual = 0;
            @endphp
            @foreach($stocks as $stock)
            @php 
                $totalBeli += $stock->harga_beli;
                $totalJual += $stock->harga_jual;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $stock->master_motor->nama_motor }}</td>
                <td>{{ $stock->master_warna->nama_warna }}</td>
                <td>{{ $stock->no_rangka }}</td>
                <td>{{ $stock->no_mesin }}</td>
                <td>{{ $stock->status }}</td>
                <td>Rp {{ number_format($stock->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($stock->harga_jual, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" style="text-align: right">Total</td>
                <td>Rp {{ number_format($totalBeli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalJual, 0, ',', '.') }}</td>
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