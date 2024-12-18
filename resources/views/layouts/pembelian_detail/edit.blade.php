@extends('layouts.app')

@section('title', 'Edit Pembelian Detail')

@section('content')
    <style>
        .modal-lg {
            max-width: 1200px;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        @media (min-width: 576px) {
            .modal-dialog {
                min-height: calc(100% - 3.5rem);
            }
        }

        .modal-content {
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal {
            overflow-y: auto;
        }

        @media (min-width: 992px) {
            .modal-lg {
                max-width: 80%;
            }
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }

        .selected-items {
            margin-bottom: 20px;
        }

        .selected-item .btn {
            margin-right: 5px;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Pembelian Detail</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('pembelian_detail.update', $pembelian->id) }}" method="POST" id="editForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="pembelian_id">Invoice Pembelian</label>
                                <input type="text" class="form-control" value="{{ $pembelian->invoice_pembelian }}"
                                    readonly>
                            </div>

                            <div id="motorSelection">
                                @foreach ($pembelian_details as $index => $detail)
                                    <div class="selected-item motor-item row mb-2">
                                        <input type="hidden" name="detail_ids[]" value="{{ $detail->id }}">
                                        <input type="hidden" name="motor_ids[]" value="{{ $detail->motor_id }}">
                                        <input type="hidden" name="warna_ids[]" value="{{ $detail->warna_id }}">
                                        <div class="col-md-3">{{ $detail->motor->nama_motor }}</div>
                                        <div class="col-md-2">{{ $detail->warna->nama_warna }}</div>
                                        <div class="col-md-2">
                                            <input type="number" name="jumlah_motors[]" class="form-control motor-quantity"
                                                value="{{ $detail->jumlah_motor }}" min="1">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" name="harga_motors[]" class="form-control motor-price"
                                                    value="{{ number_format($detail->harga_motor, 0, ',', '.') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group mb-0">
                                <label for="total_harga" class="mb-0 mr-2">Total Harga:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" name="total_harga" id="total_harga"
                                        class="form-control text-right"
                                        value="{{ number_format($pembelian->total_harga, 0, ',', '.') }}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="mt-3 mr-5">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('pembelian_detail.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
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
            function formatNumber(n) {
                return n.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function parseNumber(str) {
                return parseFloat(str.replace(/\./g, '')) || 0;
            }

            function updateTotalPrice() {
                let total = 0;
                $('.motor-item').each(function() {
                    const quantity = parseInt($(this).find('.motor-quantity').val()) || 0;
                    const price = parseNumber($(this).find('.motor-price').val());
                    total += quantity * price;
                });
                $('#total_harga').val(formatNumber(total));
            }

            $(document).on('input', '.motor-quantity, .motor-price', function() {
                updateTotalPrice();
            });

            $('.motor-price').on('blur', function() {
                $(this).val(formatNumber($(this).val()));
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                // Remove formatting from price inputs before submission
                $('.motor-price').each(function() {
                    $(this).val(parseNumber($(this).val()));
                });

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan memperbarui data pembelian detail ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(this);

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
                                    text: 'Data pembelian detail berhasil diperbarui.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            },
                            error: function(xhr) {
                                // Reformat price inputs after failed submission
                                $('.motor-price').each(function() {
                                    $(this).val(formatNumber($(this).val()));
                                });

                                let errorMessage =
                                    'Terjadi kesalahan saat memperbarui data.';
                                if (xhr.responseJSON) {
                                    if (xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    if (xhr.responseJSON.errors) {
                                        errorMessage = '<ul>';
                                        for (let field in xhr.responseJSON.errors) {
                                            errorMessage +=
                                                `<li>${xhr.responseJSON.errors[field][0]}</li>`;
                                        }
                                        errorMessage += '</ul>';
                                    }
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    html: errorMessage
                                });
                            }
                        });
                    } else {
                        // Reformat price inputs if submission is cancelled
                        $('.motor-price').each(function() {
                            $(this).val(formatNumber($(this).val()));
                        });
                    }
                });
            });

            // Initial update of total price
            updateTotalPrice();
        });
    </script>
@endpush
