@extends('layouts.app')

@section('title', 'Edit Pricing')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Pricing</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stock.updatePricing', $stock->id) }}" method="POST" id="editPricingForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Nama Motor</label>
                                <input type="text" class="form-control" value="{{ $stock->master_motor->nama_motor }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label>Warna</label>
                                <input type="text" class="form-control" value="{{ $stock->master_warna->nama_warna }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label>Harga Beli</label>
                                <input type="text" class="form-control"
                                    value="{{ number_format($stock->harga_beli, 0, ',', '.') }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="harga_jual">Harga Jual</label>
                                <input type="text" name="harga_jual" id="harga_jual" class="form-control"
                                    value="{{ old('harga_jual', number_format($stock->harga_jual, 0, ',', '.')) }}"
                                    required>
                                @error('harga_jual')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Terapkan Harga Jual Untuk:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="apply_to" id="apply_single"
                                        value="single" checked>
                                    <label class="form-check-label" for="apply_single">
                                        Hanya motor ini
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="apply_to" id="apply_all"
                                        value="all">
                                    <label class="form-check-label" for="apply_all">
                                        Semua motor dengan nama yang sama
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="apply_to" id="apply_color"
                                        value="color">
                                    <label class="form-check-label" for="apply_color">
                                        Semua motor dengan nama dan warna yang sama
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" id="colorSelectGroup" style="display: none;">
                                <label for="warna_id">Pilih Warna</label>
                                <select name="warna_id" id="warna_id" class="form-control">
                                    @foreach ($colors as $id => $color)
                                        <option value="{{ $id }}" {{ $stock->warna_id == $id ? 'selected' : '' }}>
                                            {{ $color }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Pricing</button>
                                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    <script>
        $(document).ready(function() {
            var hargaJualCleave = new Cleave('#harga_jual', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });

            $('input[name="apply_to"]').change(function() {
                if ($(this).val() === 'color') {
                    $('#colorSelectGroup').show();
                } else {
                    $('#colorSelectGroup').hide();
                }
            });

            $('#editPricingForm').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Konfirmasi Update Pricing',
                    text: "Apakah Anda yakin ingin mengupdate harga jual?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Update!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set raw value for harga_jual
                        $('#harga_jual').val(hargaJualCleave.getRawValue());

                        $.ajax({
                            url: $(form).attr('action'),
                            method: 'POST',
                            data: $(form).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Harga jual berhasil diupdate.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('stock.index') }}';
                                });
                            },
                            error: function(xhr) {
                                let errorMessage =
                                    'Terjadi kesalahan saat mengupdate harga.';
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    errorMessage = Object.values(xhr.responseJSON
                                        .errors).join('\n');
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
