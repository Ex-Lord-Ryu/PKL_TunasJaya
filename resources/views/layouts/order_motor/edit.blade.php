@extends('layouts.app')

@section('title', 'Edit Order Motor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Order Motor</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('order_motor.update', $orderMotor->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="motor_id">Motor</label>
                                    <select name="motor_id" id="motor_id" class="form-control @error('motor_id') is-invalid @enderror">
                                        <option value="">Pilih Motor</option>
                                        @foreach($motors as $motor)
                                        <option value="{{ $motor->id }}" {{ $orderMotor->motor_id == $motor->id ? 'selected' : '' }}>
                                            {{ $motor->nama_motor }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('motor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="warna_id">Warna</label>
                                    <select name="warna_id" id="warna_id" class="form-control @error('warna_id') is-invalid @enderror">
                                        <option value="">Pilih Warna</option>
                                        @foreach($warnas as $warna)
                                        <option value="{{ $warna->id }}" {{ $orderMotor->warna_id == $warna->id ? 'selected' : '' }}>
                                            {{ $warna->nama_warna }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('warna_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_motor">Jumlah Motor</label>
                                    <input type="number" name="jumlah_motor" class="form-control @error('jumlah_motor') is-invalid @enderror" value="{{ $orderMotor->jumlah_motor }}">
                                    @error('jumlah_motor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Pembayaran</label>
                                    <select name="pembayaran" id="pembayaran" class="form-control @error('pembayaran') is-invalid @enderror" required>
                                        <option value="Cash" {{ $orderMotor->pembayaran === 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Transfer" {{ $orderMotor->pembayaran === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="Kredit" {{ $orderMotor->pembayaran === 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                    </select>
                                </div>

                                <!-- Kredit Fields -->
                                <div id="kreditFields" style="display: {{ $orderMotor->pembayaran === 'Kredit' ? 'block' : 'none' }};">
                                    <div class="form-group">
                                        <label>Down Payment (DP)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="dp" id="dp" class="form-control currency" 
                                                value="{{ number_format($orderMotor->dp ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tenor</label>
                                        <select name="tenor" id="tenor" class="form-control">
                                            <option value="">Pilih Tenor</option>
                                            <option value="12" {{ $orderMotor->tenor == 12 ? 'selected' : '' }}>12 Bulan</option>
                                            <option value="24" {{ $orderMotor->tenor == 24 ? 'selected' : '' }}>24 Bulan</option>
                                            <option value="36" {{ $orderMotor->tenor == 36 ? 'selected' : '' }}>36 Bulan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Angsuran per Bulan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="angsuran" id="angsuran" class="form-control currency" 
                                                value="{{ number_format($orderMotor->angsuran ?? 0, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Total Kredit</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" id="totalKredit" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengiriman">Pengiriman</label>
                                    <input type="text" name="pengiriman" class="form-control @error('pengiriman') is-invalid @enderror" value="{{ $orderMotor->pengiriman }}">
                                    @error('pengiriman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama Pembeli</label>
                                    <input type="text" name="nama_pembeli" class="form-control @error('nama_pembeli') is-invalid @enderror" 
                                           value="{{ $orderMotor->nama_pembeli }}" required>
                                    @error('nama_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Alamat Pembeli</label>
                                    <textarea name="alamat_pembeli" class="form-control @error('alamat_pembeli') is-invalid @enderror" 
                                              rows="3" required>{{ $orderMotor->alamat_pembeli }}</textarea>
                                    @error('alamat_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. HP Pembeli</label>
                                    <input type="text" name="no_hp_pembeli" class="form-control @error('no_hp_pembeli') is-invalid @enderror" 
                                           value="{{ $orderMotor->no_hp_pembeli }}" required>
                                    @error('no_hp_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ $orderMotor->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ $orderMotor->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $orderMotor->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="no_rangka">No Rangka</label>
                                    <input type="text" name="no_rangka" class="form-control @error('no_rangka') is-invalid @enderror" value="{{ $orderMotor->no_rangka }}" readonly>
                                    @error('no_rangka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="no_mesin">No Mesin</label>
                                    <input type="text" name="no_mesin" class="form-control @error('no_mesin') is-invalid @enderror" value="{{ $orderMotor->no_mesin }}" readonly>
                                    @error('no_mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                            <a href="{{ route('order_motor.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Format currency
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Parse currency string to number
    function parseCurrency(str) {
        return parseInt(str.replace(/[^\d]/g, '')) || 0;
    }

    // Hitung angsuran
    function hitungAngsuran() {
        const hargaJual = {{ $orderMotor->harga_jual }};
        const dp = parseCurrency($('#dp').val());
        const tenor = parseInt($('#tenor').val()) || 0;
        
        if (tenor > 0) {
            // Hitung sisa yang harus dicicil
            const sisaKredit = hargaJual - dp;

            // Hitung bunga per tahun (10%)
            const bungaPerTahun = sisaKredit * 0.1;

            // Total bunga selama tenor
            const totalBunga = bungaPerTahun * (tenor / 12);

            // Total yang harus dibayar
            const totalBayar = sisaKredit + totalBunga;

            // Angsuran per bulan
            const angsuran = Math.ceil(totalBayar / tenor);

            // Update fields
            $('#angsuran').val(formatRupiah(angsuran));
            $('#totalKredit').val(formatRupiah(totalBayar));

            // Tampilkan rincian perhitungan
            const rincian = `
                <div class="alert alert-info mt-3">
                    <h6>Rincian Kredit:</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Harga Motor</td>
                            <td>: Rp ${formatRupiah(hargaJual)}</td>
                        </tr>
                        <tr>
                            <td>Down Payment (DP)</td>
                            <td>: Rp ${formatRupiah(dp)}</td>
                        </tr>
                        <tr>
                            <td>Sisa Kredit</td>
                            <td>: Rp ${formatRupiah(sisaKredit)}</td>
                        </tr>
                        <tr>
                            <td>Bunga ${tenor/12} Tahun (10% p.a.)</td>
                            <td>: Rp ${formatRupiah(totalBunga)}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>Total Kredit</td>
                            <td>: Rp ${formatRupiah(totalBayar)}</td>
                        </tr>
                        <tr>
                            <td>Angsuran per Bulan</td>
                            <td>: Rp ${formatRupiah(angsuran)}</td>
                        </tr>
                    </table>
                </div>
            `;
            $('#rincianKredit').html(rincian);
        }
    }

    // Calculate initial values if payment method is Kredit
    if ($('#pembayaran').val() === 'Kredit') {
        $('#kreditFields').show();
        hitungAngsuran(); // This will calculate and display the total credit
    }

    // Handle pembayaran change
    $('#pembayaran').on('change', function() {
        if ($(this).val() === 'Kredit') {
            $('#kreditFields').show();
            hitungAngsuran();
        } else {
            $('#kreditFields').hide();
        }
    });

    // Handle tenor change
    $('#tenor').on('change', function() {
        hitungAngsuran();
    });

    // Format currency inputs
    $('.currency').on('input', function() {
        let value = parseCurrency($(this).val());
        $(this).val(formatRupiah(value));
    });
});
</script>
@endpush
