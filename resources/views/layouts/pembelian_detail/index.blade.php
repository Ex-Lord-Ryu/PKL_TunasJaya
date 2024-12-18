@extends('layouts.app')

@section('title', 'Pembelian Detail')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pembelian Detail</h1>
            </div>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('pembelian_detail.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Pembelian Detail
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="pembeliandetailTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Invoice Pembelian</th>
                                        <th>Status</th>
                                        <th>Tanggal Pembuatan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian_detail as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['invoice_pembelian'] }}</td>
                                            <td>{{ $item['status'] }}</td>
                                            <td>{{ $item['tanggal_pembuatan']->format('d-m-Y') }}</td>
                                            <td>
                                                <a href="{{ route('pembelian_detail.show', $item['pembelian_id']) }}" 
                                                   class="btn btn-info btn-sm action-btn mr-1">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('pembelian_detail.edit', $item['pembelian_id']) }}"
                                                    class="btn btn-primary btn-sm action-btn mr-1 edit-btn">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                @if (strtolower($item['status']) == 'pending')
                                                    <button class="btn btn-success btn-sm action-btn mr-1 complete-btn"
                                                        data-id="{{ $item['pembelian_id'] }}">
                                                        <i class="fas fa-check"></i> Complete
                                                    </button>
                                                    <button class="btn btn-warning btn-sm action-btn mr-1 cancel-btn"
                                                        data-id="{{ $item['pembelian_id'] }}">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                @endif
                                                <form
                                                    action="{{ route('pembelian_detail.delete', $item['pembelian_id']) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm action-btn delete-btn">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
            let table = $('#pembeliandetailTable').DataTable({
                "responsive": true,
                "order": [
                    [0, "asc"]
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Delete confirmation
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Menghapus Data',
                    text: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Edit confirmation
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');

                Swal.fire({
                    title: 'Edit Pembelian Detail',
                    text: "Apakah Anda yakin ingin mengedit data pembelian detail ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Edit!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });

            // Complete button click handler
            $('.complete-btn').on('click', function() {
                var pembelianId = $(this).data('id');
                updateStatus(pembelianId, 'Completed');
            });

            // Cancel button click handler
            $('.cancel-btn').on('click', function() {
                var pembelianId = $(this).data('id');
                updateStatus(pembelianId, 'Cancelled');
            });

            window.updateStatus = function(pembelianId, status) {
                Swal.fire({
                    title: 'Update Status',
                    text: `Apakah Anda yakin ingin mengubah status menjadi ${status}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/pembelian_detail/${pembelianId}/update-status`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: status
                            },
                            success: function(response) {
                                console.log('AJAX success:', response);
                                if (response.success) {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX error:', xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan saat memperbarui status.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        });
    </script>
@endpush