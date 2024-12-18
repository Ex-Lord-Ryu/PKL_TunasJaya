@extends('layouts.app')

@section('title', 'Tambah Master Motor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Master Motor</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tambah Master Motor</h4>
                        </div>
                        <div class="card-body">
                            <form id="motorForm" action="{{ route('master_motor.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nama Motor</label>
                                    <input type="text" name="nama_motor" class="form-control" id="nama_motor" placeholder="Masukkan Nama Motor" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nomor Rangka</label>
                                    <input type="text" name="nomor_rangka" class="form-control" id="nomor_rangka" placeholder="Masukkan Nomor Rangka" required>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nomor Mesin</label>
                                    <input type="text" name="nomor_mesin" class="form-control" id="nomor_mesin" placeholder="Masukkan Nomor Mesin" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('master_motor.index') }}" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
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
                    title: 'Menyimpan Data',
                    text: 'Apakah Anda yakin ingin menyimpan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
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
                                    window.location.href = '{{ route('master_motor.index') }}';
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat menyimpan data.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
