@extends('layouts.app')

@section('title', 'Order Motor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Order Motor</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('order_motor.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Order Motor
                            </a>
                        </div>
                    </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="stockTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Sales</th>
                                        <th>Motor</th>
                                        <th>Jumlah</th>
                                        <th>Harga Jual</th>
                                        <th>Total</th>
                                        <th>Tanggal Order</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->user->name ?? 'User tidak ditemukan' }}</td>
                                            <td>{{ $order->master_motor->nama_motor ?? 'Motor tidak ditemukan' }}</td>
                                            <td>{{ $order->jumlah_motor }}</td>
                                            <td>Rp {{ number_format($order->harga_jual, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($order->harga_jual * $order->jumlah_motor, 0, ',', '.') }}</td>
                                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                @if($order->status === 'active')
                                                    <span class="badge badge-primary">Active</span>
                                                @elseif($order->status === 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif($order->status === 'cancelled')
                                                    <span class="badge badge-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('order_motor.show', $order->id) }}" class="btn btn-info btn-sm action-btn mr-1">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                @if($order->status === 'active')
                                                    <a href="{{ route('order_motor.edit', $order->id) }}" class="btn btn-warning btn-sm action-btn mr-1">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('order_motor.cancel', $order->id) }}" method="POST" class="d-inline cancel-form">
                                                        @csrf
                                                        <button type="button" class="btn btn-warning btn-sm cancel-btn">
                                                            <i class="fas fa-times"></i> Cancel
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('order_motor.complete', $order->id) }}" method="POST" class="d-inline complete-form">
                                                        @csrf
                                                        <button type="button" class="btn btn-success btn-sm complete-btn">
                                                            <i class="fas fa-check"></i> Complete
                                                        </button>
                                                    </form>
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
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#stockTable').DataTable({
                "responsive": true,
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

            // Cancel Order
            $('.cancel-btn').on('click', function() {
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Konfirmasi Pembatalan',
                    text: 'Apakah Anda yakin ingin membatalkan order ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Complete Order
            $('.complete-btn').on('click', function() {
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Konfirmasi Penyelesaian',
                    text: 'Apakah Anda yakin ingin menyelesaikan order ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Selesaikan!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Show success message with SweetAlert if exists
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-success',
                    },
                    buttonsStyling: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            @endif

            // Show error message with SweetAlert if exists
            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                    },
                    buttonsStyling: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            @endif
        });
    </script>
@endpush
