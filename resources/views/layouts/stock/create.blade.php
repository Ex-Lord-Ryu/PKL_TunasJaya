@extends('layouts.app')

@section('title', 'Tambah Stock')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Stock</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('stock.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="pembelian_detail_id">Pilih Motor dari Pembelian</label>
                            <select name="pembelian_detail_id" id="pembelian_detail_id" class="form-control @error('pembelian_detail_id') is-invalid @enderror" required>
                                <option value="">Pilih Motor</option>
                                @foreach($pembelianDetails as $detail)
                                    <option value="{{ $detail->id }}">
                                        {{ $detail->master_motor->nama_motor }} - 
                                        Invoice: {{ $detail->pembelian->invoice_pembelian }} - 
                                        Tanggal: {{ $detail->pembelian->tanggal_pembelian->format('d-m-Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pembelian_detail_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_rangka">Nomor Rangka</label>
                            <input type="text" name="no_rangka" id="no_rangka" class="form-control @error('no_rangka') is-invalid @enderror" required>
                            @error('no_rangka')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_mesin">Nomor Mesin</label>
                            <input type="text" name="no_mesin" id="no_mesin" class="form-control @error('no_mesin') is-invalid @enderror" required>
                            @error('no_mesin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection