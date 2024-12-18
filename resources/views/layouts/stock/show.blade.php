@extends('layouts.app')

@section('title', 'Detail Stock')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Stock</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('stock.index') }}">Stock</a></div>
                    <div class="breadcrumb-item active">Detail</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mt-4">Detail Stock</h5>
                        <div class="table-responsive">
                            <table id="detailStockTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Motor</th>
                                        <th>Warna</th>
                                        <th>No Rangka</th>
                                        <th>No Mesin</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($relatedStocks as $index => $stock)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $stock->master_motor->nama_motor ?? 'N/A' }}</td>
                                            <td>{{ $stock->master_warna->nama_warna ?? 'N/A' }}</td>
                                            <td>{{ $stock->no_rangka ?? 'N/A' }}</td>
                                            <td>{{ $stock->no_mesin ?? 'N/A' }}</td>
                                            <td>{{ number_format($stock->harga_beli, 0, ',', '.') }}</td>
                                            <td>{{ $stock->harga_jual ? number_format($stock->harga_jual, 0, ',', '.') : 'N/A' }}
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
            $('#detailStockTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data yang tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endpush
