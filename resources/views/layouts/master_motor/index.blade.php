@extends('layouts.app')

@section('title', 'Master Motor')

@section('content')
    <style>
        .table th,
        .table td {
            width: auto !important;
        }

        .action-btn {
            margin-right: 5px;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Master Motor</h1>
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
                                <a href="{{ route('master_motor.create') }}" class="btn btn-success"><i class="fas fa-plus"></i>
                                    Tambah Master Motor</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="motorTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Motor</th>
                                        <th>Nomor Rangka</th>
                                        <th>Nomor Mesin</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($master_motor as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->nama_motor }}</td>
                                            <td>{{ $item->nomor_rangka }}</td>
                                            <td>{{ $item->nomor_mesin }}</td>
                                            <td>
                                                <a href="{{ route('master_motor.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm action-btn mr-1 edit-btn">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('master_motor.delete', $item->id) }}" method="POST"
                                                    style="display: inline-block;">
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
            @else
            <div class="section-body">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="motorTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Motor</th>
                                        <th>Nomor Rangka</th>
                                        <th>Nomor Mesin</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($master_motor as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->nama_motor }}</td>
                                            <td>{{ $item->nomor_rangka }}</td>
                                            <td>{{ $item->nomor_mesin }}</td>
                                            
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
@endsection

@push('scripts')
    <script>
        let table;

        $(document).ready(function() {
            try {
                $('#motorTable').DataTable({
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

                            // Submit form
                            $.ajax({
                                url: form.attr('action'),
                                method: 'POST',
                                data: form.serialize(),
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Data motor berhasil dihapus.',
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
                    title: 'Edit Motor',
                    text: "Apakah Anda yakin ingin mengedit data motor ini?",
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
        });
    </script>
@endpush
