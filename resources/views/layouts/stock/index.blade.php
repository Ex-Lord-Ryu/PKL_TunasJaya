@extends('layouts.app')

@section('title', 'Stock')

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
                <h1>Stock</h1>
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

            <div class="section-body">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="stockTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Motor</th>
                                        <th>Invoice Pembelian</th>
                                        <th>Jumlah Motor</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($stocks as $motorName => $groupedStocks)
                                        @foreach ($groupedStocks as $invoice => $items)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $motorName }}</td>
                                                <td>{{ $invoice }}</td>
                                                <td>{{ $items->count() }}</td>
                                                <td>
                                                    <a href="{{ route('stock.show', $items->first()->id) }}"
                                                        class="btn btn-info btn-sm action-btn mr-1">
                                                        <i class="fas fa-eye"></i> Lihat Detail
                                                    </a>
                                                    <a href="{{ route('stock.inputNomor', $invoice) }}"
                                                        class="btn btn-primary btn-sm action-btn mr-1">
                                                        <i class="fas fa-edit"></i> Input Nomor
                                                    </a>
                                                    <a href="{{ route('stock.editPricing', $items->first()->id) }}"
                                                        class="btn btn-warning btn-sm action-btn mr-1">
                                                        <i class="fas fa-dollar-sign"></i> Edit Pricing
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
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
        });
    </script>
@endpush
