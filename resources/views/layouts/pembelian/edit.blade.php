@extends('layouts.app')

@section('title', 'Edit Pembelian')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Pembelian</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form id="pembelianForm" action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="vendor_id">Vendor</label>
                                <select name="vendor_id" id="vendor_id" class="form-control select2" required>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ old('vendor_id', $pembelian->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->nama_vendor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    @foreach(['Pending','Completed','Cancelled'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $pembelian->status) == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="metode_pembayaran">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                                    @foreach(['Cash', 'Transfer', 'Kredit'] as $metode)
                                        <option value="{{ $metode }}" {{ old('metode_pembayaran', $pembelian->metode_pembayaran) == $metode ? 'selected' : '' }}>
                                            {{ $metode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="metode_pengiriman">Metode Pengiriman</label>
                                <select name="metode_pengiriman" id="metode_pengiriman" class="form-control" required>
                                    @foreach(['Kapal Kargo', 'Truck', 'Pick-Up', 'Mobil-Box'] as $metode)
                                        <option value="{{ $metode }}" {{ old('metode_pengiriman', $pembelian->metode_pengiriman) == $metode ? 'selected' : '' }}>
                                            {{ $metode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_pembelian">Tanggal Pembelian</label>
                                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" class="form-control"
                                    value="{{ old('tanggal_pembelian', $pembelian->tanggal_pembelian ? ($pembelian->tanggal_pembelian instanceof \Carbon\Carbon ? $pembelian->tanggal_pembelian->format('Y-m-d') : $pembelian->tanggal_pembelian) : '') }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="tanggal_pengiriman">Tanggal Pengiriman</label>
                                <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman" class="form-control"
                                    value="{{ old('tanggal_pengiriman', $pembelian->tanggal_pengiriman ? ($pembelian->tanggal_pengiriman instanceof \Carbon\Carbon ? $pembelian->tanggal_pengiriman->format('Y-m-d') : $pembelian->tanggal_pengiriman) : '') }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="tanggal_penerimaan">Tanggal Penerimaan</label>
                                <input type="date" name="tanggal_penerimaan" id="tanggal_penerimaan" class="form-control"
                                    value="{{ old('tanggal_penerimaan', $pembelian->tanggal_penerimaan ? ($pembelian->tanggal_penerimaan instanceof \Carbon\Carbon ? $pembelian->tanggal_penerimaan->format('Y-m-d') : $pembelian->tanggal_penerimaan) : '') }}">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Pembelian</button>
                            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Batal</a>
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
                                    text: 'Data pembelian berhasil disimpan.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = '{{ route('pembelian.index') }}';
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