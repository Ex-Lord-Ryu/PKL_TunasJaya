@extends('layouts.app')

@section('title', 'Create Pembelian Detail')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create Pembelian Detail</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('pembelian_detail.store') }}" method="POST" id="pembelianDetailForm">
                            @csrf

                            <div class="form-group">
                                <label for="pembelian_id">Nomor Invoice Pembelian</label>
                                <select name="pembelian_id" id="pembelian_id" class="form-control @error('pembelian_id') is-invalid @enderror" required>
                                    <option value="">Pilih Invoice Pembelian</option>
                                    @foreach($pembelian as $p)
                                        <option value="{{ $p->id }}" {{ old('pembelian_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->invoice_pembelian }} - {{ $p->vendor->nama_vendor ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pembelian_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="detail-container">
                                <div class="detail-item">
                                    <div class="row align-items-end">
                                        <div class="col">
                                            <div class="form-group mb-0">
                                                <label>Motor</label>
                                                <select name="motor_ids[]" class="form-control motor-select" required>
                                                    <option value="">Pilih Motor</option>
                                                    @foreach($master_motor as $motor)
                                                        <option value="{{ $motor->id }}">{{ $motor->nama_motor }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group mb-0">
                                                <label>Warna</label>
                                                <select name="motor_warna_ids[]" class="form-control warna-select" required>
                                                    <option value="">Pilih Warna</option>
                                                    @foreach($master_warna as $warna)
                                                        <option value="{{ $warna->id_warna }}">{{ $warna->nama_warna }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group mb-0">
                                                <label>Jumlah</label>
                                                <input type="number" name="motor_quantities[]" class="form-control quantity-input" min="1" placeholder="1" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group mb-0">
                                                <label>Harga</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="text" name="motor_prices[]" class="form-control price-input" data-type="currency" placeholder="Rp 0" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group mb-0">
                                                <label>Subtotal</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="text" class="form-control subtotal" value="Rp 0" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-danger remove-detail">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Total Harga</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" id="total_harga" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-success" id="add-detail">Tambah Item</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('pembelian_detail.index') }}" class="btn btn-secondary">Batal</a>
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
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah ? 'Rp ' + rupiah : '';
    }

    // Parse currency string to number
    function parseCurrency(str) {
        return parseInt(str.replace(/[^\d]/g, '')) || 0;
    }

    // Handle currency input with better formatting
    $(document).on('input', '.price-input', function(e) {
        let cursorPos = this.selectionStart;
        let value = $(this).val();
        let length = value.length;
        
        // Remove non-digit chars
        value = value.replace(/[^\d]/g, '');
        
        // Format the number
        let formatted = formatRupiah(value);
        
        // Update the input value
        $(this).val(formatted);
        
        // Handle cursor position after formatting
        cursorPos = formatted.length - length + cursorPos;
        this.setSelectionRange(cursorPos, cursorPos);
    });

    // Format existing price inputs on page load
    $('.price-input').each(function() {
        let value = $(this).val();
        if (value) {
            $(this).val(formatRupiah(value));
        }
    });

    // Fungsi untuk menghitung total harga
    function calculateTotal() {
        let total = 0;
        $('.detail-item').each(function() {
            const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
            const price = parseCurrency($(this).find('.price-input').val());
            total += quantity * price;
        });
        $('#total_harga').val(formatRupiah(total.toString()));
    }

    // Fungsi untuk update subtotal per baris
    function updateRowTotal(row) {
        const quantity = parseInt(row.find('.quantity-input').val()) || 0;
        const price = parseCurrency(row.find('.price-input').val());
        const subtotal = quantity * price;
        row.find('.subtotal').val(formatRupiah(subtotal.toString()));
    }

    // Event handler untuk tambah item (hanya satu instance)
    $('#add-detail').on('click', function() {
        let newDetail = $('.detail-item:first').clone();
        newDetail.find('input').val('');
        newDetail.find('select').val('');
        newDetail.find('.price-input').attr('placeholder', 'Rp 0');
        newDetail.find('.subtotal').val('Rp 0');
        $('#detail-container').append(newDetail);
        calculateTotal();
    });

    // Event handler untuk hapus item
    $(document).on('click', '.remove-detail', function() {
        if ($('.detail-item').length > 1) {
            $(this).closest('.detail-item').remove();
            calculateTotal();
        }
    });

    // Update subtotal dan total saat input berubah
    $(document).on('input', '.quantity-input, .price-input', function() {
        const row = $(this).closest('.detail-item');
        updateRowTotal(row);
        calculateTotal();
    });

    // Format existing values on page load
    $('.detail-item').each(function() {
        updateRowTotal($(this));
    });
    calculateTotal();

    // Form submission with proper price handling
    $('#pembelianDetailForm').on('submit', function(e) {
        e.preventDefault();

        // Validate form
        let isValid = true;
        const pembelian_id = $('#pembelian_id').val();

        if (!pembelian_id) {
            isValid = false;
            $('#pembelian_id').addClass('is-invalid');
        }

        // Validate each detail row
        $('.detail-item').each(function() {
            const motor = $(this).find('.motor-select').val();
            const warna = $(this).find('.warna-select').val();
            const quantity = $(this).find('.quantity-input').val();
            const price = parseCurrency($(this).find('.price-input').val());

            if (!motor || !warna || !quantity || price === 0) {
                isValid = false;
                if (!motor) $(this).find('.motor-select').addClass('is-invalid');
                if (!warna) $(this).find('.warna-select').addClass('is-invalid');
                if (!quantity) $(this).find('.quantity-input').addClass('is-invalid');
                if (price === 0) $(this).find('.price-input').addClass('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Mohon lengkapi semua field yang diperlukan!'
            });
            return;
        }

        // Prepare form data
        const formData = new FormData(this);

        // Convert price to number before sending
        $('.price-input').each(function(index) {
            let price = parseCurrency($(this).val());
            formData.set(`motor_prices[${index}]`, price);
        });

        // Submit form
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data pembelian detail berhasil disimpan.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = response.redirect;
                });
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                if (xhr.responseJSON) {
                    errorMessage = xhr.responseJSON.message || errorMessage;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMessage
                });
            }
        });
    });

    // Reset invalid state on input change
    $(document).on('change', '.form-control', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush
