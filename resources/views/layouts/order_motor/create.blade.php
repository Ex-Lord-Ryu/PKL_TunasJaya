@extends('layouts.app')

@section('title', 'Tambah Order Motor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Order Motor</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('order_motor.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="motor-orders">
                                    <div class="motor-order border p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pilih Motor dari Stock</label>
                                                    <select name="orders[0][stock_id]" class="form-control stock-select">
                                                        <option value="">Pilih Motor</option>
                                                        @foreach($stocks as $stock)
                                                            @if($stock->no_rangka && $stock->no_mesin && $stock->harga_jual)
                                                            <option value="{{ $stock->id }}" 
                                                                    data-motor="{{ $stock->motor_id }}"
                                                                    data-warna="{{ $stock->warna_id }}"
                                                                    data-norangka="{{ $stock->no_rangka }}"
                                                                    data-nomesin="{{ $stock->no_mesin }}"
                                                                    data-harga="{{ $stock->harga_jual }}">
                                                                {{ $stock->master_motor->nama_motor }} - 
                                                                {{ $stock->master_warna->nama_warna }}
                                                            </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <input type="hidden" name="orders[0][motor_id]" class="motor-id">
                                                <input type="hidden" name="orders[0][warna_id]" class="warna-id">
                                                <input type="hidden" name="orders[0][no_rangka]" class="no-rangka">
                                                <input type="hidden" name="orders[0][no_mesin]" class="no-mesin">
                                                <input type="hidden" name="orders[0][harga_jual]" class="harga-jual">

                                                <div class="form-group">
                                                    <label>Jumlah</label>
                                                    <input type="number" name="orders[0][jumlah_motor]" class="form-control" value="1" min="1" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Harga Motor</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control harga-display" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-info mb-3" id="addMotor">
                                    <i class="fas fa-plus"></i> Tambah Motor Lain
                                </button>

                                <div class="form-group">
                                    <label>Pengiriman</label>
                                    <select name="pengiriman" class="form-control @error('pengiriman') is-invalid @enderror" required>
                                        <option value="">Pilih Metode Pengiriman</option>
                                        <option value="Truck">Truck</option>
                                        <option value="Pick-Up">Pick-Up</option>
                                        <option value="Mobil-Box">Mobil Box</option>
                                        <option value="Ambil Ditempat">Ambil Ditempat</option>
                                    </select>
                                    @error('pengiriman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama Pembeli</label>
                                    <input type="text" name="nama_pembeli" class="form-control @error('nama_pembeli') is-invalid @enderror" required>
                                    @error('nama_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Alamat Pembeli</label>
                                    <textarea name="alamat_pembeli" class="form-control @error('alamat_pembeli') is-invalid @enderror" rows="3" required></textarea>
                                    @error('alamat_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. HP Pembeli</label>
                                    <input type="text" name="no_hp_pembeli" class="form-control @error('no_hp_pembeli') is-invalid @enderror" required>
                                    @error('no_hp_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Pembayaran</label>
                                    <select name="pembayaran" id="pembayaran" class="form-control @error('pembayaran') is-invalid @enderror" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Transfer">Transfer</option>
                                        <option value="Kredit">Kredit</option>
                                    </select>
                                    @error('pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="kreditFields" style="display: none;">
                                    <div class="form-group">
                                        <label>Down Payment (DP)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="dp" id="dp" class="form-control currency @error('dp') is-invalid @enderror" 
                                                   placeholder="Masukkan jumlah DP" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Min DP (10%): <span id="minDP">0</span></span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">DP bisa 0 atau minimal 10% dari harga motor</small>
                                        @error('dp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Tenor</label>
                                        <select name="tenor" id="tenor" class="form-control @error('tenor') is-invalid @enderror">
                                            <option value="">Pilih Tenor</option>
                                            <option value="12">12 Bulan</option>
                                            <option value="24">24 Bulan</option>
                                            <option value="36">36 Bulan</option>
                                        </select>
                                        @error('tenor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Angsuran per Bulan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="angsuran" id="angsuran" class="form-control" readonly>
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

                                    <div id="rincianKredit"></div>

                                    <div class="alert alert-info">
                                        <strong>Informasi Kredit:</strong>
                                        <ul>
                                            <li>Bunga: 10% per tahun (flat)</li>
                                            <li>DP minimal 10% dari harga motor</li>
                                            <li>DP 0% tersedia</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('order_motor.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
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
    let motorCount = 0;

    function updateStockSelect(newSelect) {
        $(newSelect).select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    }

    function bindStockSelectEvent(container) {
        container.find('.stock-select').on('change', function() {
            const selected = $(this).find('option:selected');
            const container = $(this).closest('.motor-order');
            
            // Ambil data dari option yang dipilih
            const motorId = selected.data('motor');
            const warnaId = selected.data('warna');
            const hargaJual = selected.data('harga');
            
            // Set nilai ke input hidden
            container.find('.motor-id').val(motorId);
            container.find('.warna-id').val(warnaId);
            container.find('.no-rangka').val(selected.data('norangka'));
            container.find('.no-mesin').val(selected.data('nomesin'));
            container.find('.harga-jual').val(hargaJual);
            
            // Tampilkan harga motor dengan format Rupiah
            if (hargaJual) {
                container.find('.harga-display').val('Rp ' + formatRupiah(hargaJual));
            } else {
                container.find('.harga-display').val('');
            }
            
            hitungKredit(); // Hitung ulang kredit setiap kali motor dipilih
        });
    }

    // Initialize first select2
    $('.stock-select').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    bindStockSelectEvent($('.motor-order'));

    $('#addMotor').click(function() {
        motorCount++;
        const newMotorHtml = $('.motor-order').first().clone();
        
        // Destroy existing select2 before cloning
        newMotorHtml.find('.stock-select').select2('destroy');
        
        // Update names and IDs
        newMotorHtml.find('select, input').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace('[0]', `[${motorCount}]`));
            }
        });

        // Clear values
        newMotorHtml.find('select').val('');
        newMotorHtml.find('input[type="hidden"]').val('');
        newMotorHtml.find('input[type="number"]').val(1);
        newMotorHtml.find('.harga-display').val(''); // Clear harga display

        // Add remove button
        newMotorHtml.append('<button type="button" class="btn btn-danger remove-motor mt-2"><i class="fas fa-trash"></i> Hapus Motor</button>');

        $('.motor-orders').append(newMotorHtml);
        
        // Initialize new select2
        updateStockSelect(newMotorHtml.find('.stock-select'));
        bindStockSelectEvent(newMotorHtml);
        hitungKredit();
    });

    // Remove motor
    $(document).on('click', '.remove-motor', function() {
        $(this).closest('.motor-order').remove();
        hitungKredit();
    });

    $('#pembayaran').on('change', function() {
        if ($(this).val() === 'Kredit') {
            $('#kreditFields').show();
            hitungKredit();
        } else {
            $('#kreditFields').hide();
        }
    });

    // Initialize select2 for pembayaran and pengiriman
    $('#pembayaran, select[name="pengiriman"]').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Format currency
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Parse currency string to number
    function parseCurrency(str) {
        return parseInt(str.replace(/[^\d]/g, '')) || 0;
    }

    // Format input to currency
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value != "") {
            value = parseInt(value);
            input.value = formatRupiah(value);
        }
    }

    // Hitung total harga semua motor
    function hitungTotalHarga() {
        let total = 0;
        $('.motor-order').each(function() {
            const harga = parseInt($(this).find('.harga-jual').val() || '0');
            const jumlah = parseInt($(this).find('input[name$="[jumlah_motor]"]').val() || '0');
            total += harga * jumlah;
        });
        return total;
    }

    // Hitung kredit
    function hitungKredit() {
        const totalHarga = hitungTotalHarga();
        const dp = parseCurrency($('#dp').val());
        const tenor = parseInt($('#tenor').val()) || 0;
        
        // Update minimum DP display
        const minDP = Math.ceil(totalHarga * 0.1);
        $('#minDP').text('Rp ' + formatRupiah(minDP));

        if (tenor > 0) {
            // Hitung sisa yang harus dicicil
            const sisaKredit = totalHarga - dp;

            // Hitung bunga per tahun (10%)
            const bungaPerTahun = sisaKredit * 0.1;

            // Total bunga selama tenor
            const totalBunga = bungaPerTahun * (tenor / 12);

            // Total yang harus dibayar
            const totalKredit = sisaKredit + totalBunga;

            // Angsuran per bulan
            const angsuran = Math.ceil(totalKredit / tenor);

            // Update fields
            $('#angsuran').val('Rp ' + formatRupiah(angsuran));
            $('#totalKredit').val('Rp ' + formatRupiah(totalKredit));

            // Tampilkan rincian perhitungan
            const rincian = `
                <div class="alert alert-info mt-3">
                    <h6>Rincian Kredit:</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Harga Motor</td>
                            <td>: Rp ${formatRupiah(totalHarga)}</td>
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
                            <td>: Rp ${formatRupiah(totalKredit)}</td>
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

    // Event handlers
    $('.motor-order').on('change', 'select, input', function() {
        hitungKredit();
    });

    $('#tenor').on('change', function() {
        hitungKredit();
    });

    $('#dp').on('input', function() {
        formatCurrency(this);
        hitungKredit();
    });

    // Update perhitungan saat metode pembayaran berubah
    $('#pembayaran').on('change', function() {
        if ($(this).val() === 'Kredit') {
            $('#kreditFields').show();
            hitungKredit();
        } else {
            $('#kreditFields').hide();
        }
    });

    // Event handler untuk tombol tambah motor
    $('#addMotor').click(function() {
        // ... existing code ...
        hitungKredit();
    });

    // Event handler untuk tombol hapus motor
    $(document).on('click', '.remove-motor', function() {
        $(this).closest('.motor-order').remove();
        hitungKredit();
    });
});
</script>
@endpush

@push('styles')
<style>
.select2-container--bootstrap4 .select2-selection--single {
    height: calc(2.25rem + 2px);
    padding: .375rem .75rem;
}
</style>
@endpush
