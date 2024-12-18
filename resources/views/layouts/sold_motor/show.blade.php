@extends('layouts.app')

@section('title', 'Detail Motor Terjual')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Motor Terjual</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('sold_motor.index') }}">Motor Terjual</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Motor</h5>
                            <table class="table">
                                <tr>
                                    <th width="200">Nama Motor</th>
                                    <td>{{ $soldMotor->motor->nama_motor ?? 'Motor tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Warna</th>
                                    <td>{{ $soldMotor->warna->nama_warna ?? 'Warna tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>No Rangka</th>
                                    <td>{{ $soldMotor->no_rangka }}</td>
                                </tr>
                                <tr>
                                    <th>No Mesin</th>
                                    <td>{{ $soldMotor->no_mesin }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Penjualan</th>
                                    <td>{{ $soldMotor->tanggal_penjualan->format('d-m-Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Pembeli</h5>
                            <table class="table">
                                <tr>
                                    <th width="200">Nama Pembeli</th>
                                    <td>{{ $soldMotor->nama_pembeli }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $soldMotor->alamat_pembeli }}</td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td>{{ $soldMotor->no_hp_pembeli }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Informasi Pembayaran</h5>
                            <table class="table">
                                <tr>
                                    <th width="200">Harga</th>
                                    <td>Rp {{ number_format($soldMotor->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td>Rp {{ number_format($soldMotor->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>{{ $soldMotor->metode_pembayaran }}</td>
                                </tr>
                                @if($soldMotor->metode_pembayaran == 'Kredit')
                                <tr>
                                    <th>DP</th>
                                    <td>Rp {{ number_format($soldMotor->dp, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Tenor</th>
                                    <td>{{ $soldMotor->tenor }} Bulan</td>
                                </tr>
                                <tr>
                                    <th>Angsuran</th>
                                    <td>Rp {{ number_format($soldMotor->angsuran, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('sold_motor.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 