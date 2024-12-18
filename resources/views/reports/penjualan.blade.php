@extends('layouts.app')

@section('title', 'Laporan Penjualan Motor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Penjualan Motor</h1>
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
                            <form action="{{ route('report.penjualan') }}" method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                    <label for="month" class="mr-2">Bulan:</label>
                                    <select name="month" id="month" class="form-control">
                                        <option value="">Pilih Bulan</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::create()->month($i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label for="year" class="mr-2">Tahun:</label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="">Pilih Tahun</option>
                                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('report.penjualan.pdf') }}?month={{ request('month') }}&year={{ request('year') }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('report.penjualan.excel') }}?month={{ request('month') }}&year={{ request('year') }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="reportTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Motor</th>
                                    <th>Pembeli</th>
                                    <th>Warna</th>
                                    <th>No Rangka</th>
                                    <th>No Mesin</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($soldMotors as $index => $sold)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sold->tanggal_penjualan->format('d-m-Y') }}</td>
                                    <td>{{ $sold->motor->nama_motor ?? 'Motor tidak ditemukan' }}</td>
                                    <td>{{ $sold->nama_pembeli }}</td>
                                    <td>{{ $sold->warna->nama_warna ?? 'Warna tidak ditemukan' }}</td>
                                    <td>{{ $sold->no_rangka }}</td>
                                    <td>{{ $sold->no_mesin }}</td>
                                    <td>Rp {{ number_format($sold->total_harga, 0, ',', '.') }}</td>
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