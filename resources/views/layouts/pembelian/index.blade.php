@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            table-layout: fixed;
            width: 100%;
            min-width: 1500px;
        }

        .table th:nth-child(1) {
            width: 10%;
        }

        .table th:nth-child(2) {
            width: 25%;
        }

        .table th:nth-child(3) {
            width: 15%;
        }

        .table th:nth-child(4) {
            width: 10%;
        }

        .table th:nth-child(5) {
            width: 15%;
        }

        .table th:nth-child(6) {
            width: 40%;
        }

        .action-btn {
            margin-right: 5px;
        }

        .modal-receipt {
            font-family: 'Courier New', Courier, monospace;
        }

        .modal-receipt h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-receipt table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .modal-receipt th,
        .modal-receipt td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .modal-receipt th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .modal-receipt tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .modal-receipt tr:hover {
            background-color: #f5f5f5;
        }

        .btn {
            margin-top: 5px;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pembelian</h1>
            </div>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('pembelian.create') }}" class="btn btn-success"><i
                                        class="fas fa-plus"></i> Tambah Pembelian</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="pembelianTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Vendor</th>
                                        <th>Invoice</th>
                                        <th>Status</th>
                                        <th>Tanggal Pembelian</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->vendor->nama_vendor }}</td>
                                            <td>{{ $item->invoice_pembelian }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->tanggal_pembelian->format('d-m-Y') }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm action-btn mr-1 detail-btn"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>

                                                @if (strtolower($item->status) == 'pending')
                                                    <a href="{{ route('pembelian.edit', $item->id) }}"
                                                        class="btn btn-primary btn-sm action-btn mr-1 edit-btn">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('pembelian.delete', $item->id) }}"
                                                        method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm action-btn delete-btn">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-success btn-sm action-btn mr-1 complete-btn"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fas fa-check"></i> Complete
                                                    </button>
                                                    <button class="btn btn-warning btn-sm action-btn mr-1 cancel-btn"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                @endif

                                                @if (strtolower($item->status) != 'cancelled')
                                                    <button
                                                        class="btn btn-warning btn-sm action-btn mr-1 update-tanggal-btn"
                                                        data-id="{{ $item->id }}" data-type="pengiriman">
                                                        <i class="fas fa-truck"></i> Update Pengiriman
                                                    </button>
                                                    <button
                                                        class="btn btn-success btn-sm action-btn mr-1 update-tanggal-btn"
                                                        data-id="{{ $item->id }}" data-type="penerimaan">
                                                        <i class="fas fa-check"></i> Update Penerimaan
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="section-body">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="pembelianTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Vendor</th>
                                        <th>Invoice</th>
                                        <th>Status</th>
                                        <th>Tanggal Pembelian</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->vendor->nama_vendor }}</td>
                                            <td>{{ $item->invoice_pembelian }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->tanggal_pembelian->format('d-m-Y') }}</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pembelian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-receipt">
                    <h4>Tabel Pembelian</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Keterangan</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Invoice</td>
                                <td id="modal-invoice"></td>
                            </tr>
                            <tr>
                                <td>Vendor</td>
                                <td id="modal-vendor"></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td id="modal-status"></td>
                            </tr>
                            <tr>
                                <td>Metode Pembayaran</td>
                                <td id="modal-metode-pembayaran"></td>
                            </tr>
                            <tr>
                                <td>Metode Pengiriman</td>
                                <td id="modal-metode-pengiriman"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Pembelian</td>
                                <td id="modal-tanggal-pembelian"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengiriman</td>
                                <td id="modal-tanggal-pengiriman"></td>
                                <td>
                                    <button class="btn btn-warning btn-sm update-tanggal-btn" data-type="pengiriman">
                                        <i class="fas fa-truck"></i> Update
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Penerimaan</td>
                                <td id="modal-tanggal-penerimaan"></td>
                                <td>
                                    <button class="btn btn-success btn-sm update-tanggal-btn" data-type="penerimaan">
                                        <i class="fas fa-check"></i> Update
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table;

        $(document).ready(function() {
            try {
                $('#pembelianTable').DataTable({
                    "responsive": true,
                    "serverSide": false,
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
                    },
                    "drawCallback": function(settings) {
                        initializeDeleteConfirmation();
                    }
                });
            } catch (error) {
                console.error('Error initializing DataTable:', error);
            }

            function initializeDeleteConfirmation() {
                $('.delete-btn').off('click').on('click', function(e) {
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
                            Swal.fire({
                                title: 'Menghapus...',
                                text: 'Mohon tunggu sebentar.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: form.attr('action'),
                                method: 'POST',
                                data: form.serialize(),
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Data pembelian berhasil dihapus.',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        if (table) {
                                            table.ajax.reload();
                                        } else {
                                            location.reload();
                                        }
                                    });
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Terjadi kesalahan saat menghapus data.'
                                    });
                                }
                            });
                        }
                    });
                });
            }

            // Inisialisasi untuk halaman pertama
            initializeDeleteConfirmation();

            // Edit confirmation
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');

                Swal.fire({
                    title: 'Edit Pembelian',
                    text: "Apakah Anda yakin ingin mengedit data pembelian ini?",
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

            //update tanggal pengiriman dan penerimaan
            $(document).on('click', '.update-tanggal-btn', function() {
                var id = $(this).data('id') || curretPembelianId;
                var type = $(this).data('type');
                updateTanggal(id, type);
            });

            function updateTanggal(id, type) {
                Swal.fire({
                    title: 'Update Tanggal ' + (type === 'pengiriman' ? 'Pengiriman' :
                        'Penerimaan'),
                    text: "Apakah Anda yakin ingin mengupdate tanggal " + (type ===
                        'pengiriman' ? 'pengiriman' : 'penerimaan') + " ke waktu sekarang?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Update!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/pembelian/update-tanggal/' + id,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                type: type,
                                date: new Date().toISOString()
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Updated!',
                                        'Tanggal berhasil diperbarui.',
                                        'success');
                                    if (table) {
                                        table.ajax.reload();
                                    }
                                    if ($('#detailModal').hasClass('show')) {
                                        var formattedDate = new Date(response.newDate)
                                            .toLocaleDateString('id-ID', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: 'numeric'
                                            }).split('/').join('-');
                                        $('#modal-tanggal-' + type).text(formattedDate);
                                    }
                                } else {
                                    Swal.fire('Error', 'Gagal memperbarui tanggal.',
                                        'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error',
                                    'Terjadi kesalahan saat memperbarui tanggal.',
                                    'error');
                            }
                        });
                    }
                });
            }

            // Complete button functionality
            $(document).on('click', '.complete-btn', function() {
                var id = $(this).data('id');
                updateStatus(id, 'completed');
            });

            // Cancel button functionality
            $(document).on('click', '.cancel-btn', function() {
                var id = $(this).data('id');
                updateStatus(id, 'cancelled');
            });

            function updateStatus(id, status) {
                Swal.fire({
                    title: 'Update Status',
                    text: `Are you sure you want to mark this purchase as ${status}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/pembelian/${id}/update-status`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: status
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Updated!',
                                        `Purchase status has been updated to ${status}.`,
                                        'success'
                                    );
                                    if (table) {
                                        table.ajax.reload();
                                    } else {
                                        location.reload();
                                    }
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to update purchase status: ' + (response
                                            .message || 'Unknown error'),
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', status, error);
                                console.error('Response:', xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while updating the status: ' + error,
                                    'error'
                                );
                            }
                        });
                    }
                });
            }

            // Detail modal
            let curretPembelianId;

            $(document).on('click', '.detail-btn', function() {
                curretPembelianId = $(this).data('id');
                var id = $(this).data('id');

                function formatDate(dateString) {
                    if (!dateString) return '-';
                    var date = new Date(dateString);
                    return date.getDate().toString().padStart(2, '0') + '-' +
                        (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                        date.getFullYear();
                }

                $.ajax({
                    url: '/pembelian/' + id,
                    method: 'GET',
                    success: function(response) {
                        $('#modal-invoice').text(response.invoice_pembelian);
                        $('#modal-vendor').text(response.vendor.nama_vendor);
                        $('#modal-status').text(response.status);
                        $('#modal-metode-pembayaran').text(response.metode_pembayaran);
                        $('#modal-metode-pengiriman').text(response.metode_pengiriman);
                        $('#modal-tanggal-pembelian').text(formatDate(response
                            .tanggal_pembelian));
                        $('#modal-tanggal-pengiriman').text(formatDate(response
                            .tanggal_pengiriman));
                        $('#modal-tanggal-penerimaan').text(formatDate(response
                            .tanggal_penerimaan));
                        $('#detailModal').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengambil data.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
