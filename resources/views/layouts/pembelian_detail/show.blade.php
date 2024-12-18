@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Pembelian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('pembelian_detail.index') }}">Pembelian Detail</a></div>
                    <div class="breadcrumb-item active">Detail</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Pembelian</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Invoice:</strong> {{ $pembelian->invoice_pembelian }}</p>
                                <p><strong>Tanggal Pembuatan:</strong> {{ $pembelian->tanggal_pembuatan->format('d-m-Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> {{ $pembelian->status }}</p>
                                <!-- Tambahkan informasi lain yang relevan -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Detail Item</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="detailTable">
                                <thead>
                                    <tr>
                                        <th>Motor</th>
                                        <th>Warna</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $detail)
                                        <tr>
                                            <td>{{ $detail['motor'] }}</td>
                                            <td>{{ $detail['warna'] }}</td>
                                            <td>{{ $detail['jumlah_motor'] }}</td>
                                            <td>{{ 'Rp ' . number_format($detail['harga_motor'], 0, ',', '.') }}</td>
                                            <td>{{ 'Rp ' . number_format($detail['total_harga'], 0, ',', '.') }}</td>
                                            <td>{{ $detail['status'] }}</td>
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
            $('#detailTable').DataTable({
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
        });
    </script>
@endpush