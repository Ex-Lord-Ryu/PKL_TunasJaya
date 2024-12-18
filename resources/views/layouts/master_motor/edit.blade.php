@extends('layouts.app')

@section('title', 'Edit Master Motor')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Master Motor</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Master Motor</h4>
                    </div>
                    <div class="card-body">
                        <form id="motorForm" action="{{ route('master_motor.update', $master_motor->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Nama Motor</label>
                                <input type="text" name="nama_motor" id="nama_motor"
                                    class="form-control @error('nama_motor') is-invalid @enderror"
                                    value="{{ old('nama_motor', $master_motor->nama_motor) }}">
                                @error('nama_motor')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Nomor Rangka</label>
                                <input type="text" name="nomor_rangka" id="nomor_rangka"
                                    class="form-control @error('nomor_rangka') is-invalid @enderror"
                                    value="{{ old('nomor_rangka', $master_motor->nomor_rangka) }}">
                                @error('nomor_rangka')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Nomor Mesin</label>
                                <input type="text" name="nomor_mesin" id="nomor_mesin"
                                    class="form-control @error('nomor_mesin') is-invalid @enderror"
                                    value="{{ old('nomor_mesin', $master_motor->nomor_mesin) }}">
                                @error('nomor_mesin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('master_motor.index') }}" class="btn btn-secondary">Batal</a>
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
            $('#motorForm').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Mengupdate Data',
                    text: 'Apakah Anda yakin ingin mengupdate data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
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
                                    text: 'Data master motor berhasil disimpan.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('master_motor.index') }}';
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
