@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Pembelian</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('pembelian.store') }}" method="POST" id="pembelianForm">
                            @csrf

                            <div class="form-group">
                                <label for="vendor_id">Vendor</label>
                                <select name="vendor_id" id="vendor_id" class="form-control @error('vendor_id') is-invalid @enderror" required>
                                    <option value="">Pilih Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->nama_vendor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="metode_pembayaran">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-control @error('metode_pembayaran') is-invalid @enderror" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="Cash" {{ old('metode_pembayaran') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Transfer" {{ old('metode_pembayaran') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="Kredit" {{ old('metode_pembayaran') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                </select>
                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="metode_pengiriman">Metode Pengiriman</label>
                                <select name="metode_pengiriman" id="metode_pengiriman" class="form-control @error('metode_pengiriman') is-invalid @enderror" required>
                                    <option value="">Pilih Metode Pengiriman</option>
                                    <option value="Kapal Kargo" {{ old('metode_pengiriman') == 'Kapal Kargo' ? 'selected' : '' }}>Kapal Kargo</option>
                                    <option value="Truck" {{ old('metode_pengiriman') == 'Truck' ? 'selected' : '' }}>Truck</option>
                                    <option value="Pick-Up" {{ old('metode_pengiriman') == 'Pick-Up' ? 'selected' : '' }}>Pick-Up</option>
                                    <option value="Mobil-Box" {{ old('metode_pengiriman') == 'Mobil-Box' ? 'selected' : '' }}>Mobil-Box</option>
                                </select>
                                @error('metode_pengiriman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="hidden" name="status" value="Pending">

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
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
    $('#pembelianForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi form
        let isValid = true;
        const vendor_id = $('#vendor_id').val();
        const metode_pembayaran = $('#metode_pembayaran').val();
        const metode_pengiriman = $('#metode_pengiriman').val();

        if (!vendor_id) {
            isValid = false;
            $('#vendor_id').addClass('is-invalid');
        }
        if (!metode_pembayaran) {
            isValid = false;
            $('#metode_pembayaran').addClass('is-invalid');
        }
        if (!metode_pengiriman) {
            isValid = false;
            $('#metode_pengiriman').addClass('is-invalid');
        }

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Mohon lengkapi semua field yang diperlukan!'
            });
            return;
        }

        // Konfirmasi sebelum submit
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menyimpan data pembelian ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = this;
                const formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data pembelian berhasil disimpan.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '{{ route("pembelian.index") }}';
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
            }
        });
    });

    // Reset invalid state saat input berubah
    $('.form-control').on('change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush