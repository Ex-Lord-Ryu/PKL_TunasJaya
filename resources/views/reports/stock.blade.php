@extends('layouts.app')

@section('title', 'Laporan Stock Motor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Stock Motor</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('report.stock') }}" method="GET">
                                <div class="form-group d-flex align-items-end">
                                    <div class="mr-2" style="flex: 1;">
                                        <label for="status">Status:</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                        </select>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('report.stock.pdf') }}?status={{ request('status') }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('report.stock.excel') }}?status={{ request('status') }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="reportTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Motor</th>
                                    <th>Warna</th>
                                    <th>No Rangka</th>
                                    <th>No Mesin</th>
                                    <th>Status</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stock->master_motor->nama_motor ?? 'Motor tidak ditemukan' }}</td>
                                    <td>{{ $stock->master_warna->nama_warna ?? 'Warna tidak ditemukan' }}</td>
                                    <td>{{ $stock->no_rangka }}</td>
                                    <td>{{ $stock->no_mesin }}</td>
                                    <td>{{ $stock->status }}</td>
                                    <td>Rp {{ number_format($stock->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($stock->harga_jual, 0, ',', '.') }}</td>
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
        let table = $('#reportTable').DataTable({
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