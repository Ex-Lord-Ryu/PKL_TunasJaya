@extends('layouts.app')

@section('title', 'Detail Order Motor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Order Motor</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('order_motor.index') }}">Order Motor</a></div>
                <div class="breadcrumb-item">Detail Order</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px">Sales</th>
                                    <td>{{ $orderMotor->user->name ?? 'User tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pembeli</th>
                                    <td>{{ $orderMotor->nama_pembeli ?? 'Nama Pembeli tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat Pembeli</th>
                                    <td>{{ $orderMotor->alamat_pembeli ?? 'Alamat Pembeli tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>No HP Pembeli</th>
                                    <td>{{ $orderMotor->no_hp_pembeli ?? 'No HP Pembeli tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Motor</th>
                                    <td>{{ $orderMotor->master_motor->nama_motor ?? 'Motor tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Warna</th>
                                    <td>{{ $orderMotor->master_warna->nama_warna ?? 'Warna tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Motor</th>
                                    <td>{{ $orderMotor->jumlah_motor }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px">Pengiriman</th>
                                    <td>{{ $orderMotor->pengiriman }}</td>
                                </tr>
                                <tr>
                                    <th>Pembayaran</th>
                                    <td>{{ $orderMotor->pembayaran }}</td>
                                </tr>
                                <tr>
                                    <th>No Rangka</th>
                                    <td>{{ $orderMotor->no_rangka }}</td>
                                </tr>
                                <tr>
                                    <th>No Mesin</th>
                                    <td>{{ $orderMotor->no_mesin }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Order</th>
                                    <td>{{ $orderMotor->created_at ? $orderMotor->created_at->format('d-m-Y H:i:s') : 'Tanggal tidak ditemukan' }}</td>
                                </tr>
                                @if($orderMotor->pembayaran === 'Kredit')
                                <tr>
                                    <th>Down Payment (DP)</th>
                                    <td>Rp {{ number_format($orderMotor->dp, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Tenor</th>
                                    <td>{{ $orderMotor->tenor }} Bulan</td>
                                </tr>
                                <tr>
                                    <th>Angsuran per Bulan</th>
                                    <td>Rp {{ number_format($orderMotor->angsuran, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <a href="{{ route('order_motor.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('order_motor.edit', ['orderMotor' => $orderMotor->id]) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 