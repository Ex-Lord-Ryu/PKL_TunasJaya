@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Vendor</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form id="vendorForm" action="{{ route('vendor.update', $vendor->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama_vendor">Nama Vendor</label>
                                <input type="text" name="nama_vendor" id="nama_vendor"
                                    class="form-control @error('nama_vendor') is-invalid @enderror"
                                    value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required>
                                @error('nama_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $vendor->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Batal</a>
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
            $('#vendorForm').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Mengupdate Data',
                    text: 'Apakah Anda yakin ingin mengupdate data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Update!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengupdate...',
                            text: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        $.ajax({
                            url: $(this).attr('action'),
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data vendor berhasil diupdate.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('vendor.index') }}';
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat mengupdate data.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
