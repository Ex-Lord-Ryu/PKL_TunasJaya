@extends('layouts.app')

@section('title', 'Motor Terjual')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Motor Terjual</h1>
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
                        <div class="col-md-3">
                            <select id="year-filter" class="form-control">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="stockTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Motor</th>
                                    <th>Pembeli</th>
                                    <th>Warna</th>
                                    <th>No Rangka</th>
                                    <th>No Mesin</th>
                                    <th>Harga</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($soldMotors as $motor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $motor->tanggal_penjualan->format('d-m-Y') }}</td>
                                        <td>{{ $motor->motor->nama_motor ?? 'Motor tidak ditemukan' }}</td>
                                        <td>{{ $motor->nama_pembeli }}</td>
                                        <td>{{ $motor->warna->nama_warna ?? 'Warna tidak ditemukan' }}</td>
                                        <td>{{ $motor->no_rangka }}</td>
                                        <td>{{ $motor->no_mesin }}</td>
                                        <td>Rp {{ number_format($motor->harga, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('sold_motor.show', $motor->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
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
            let table = $('#stockTable').DataTable({
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

            $('#year-filter').change(function() {
                table.column(1).search(this.value).draw();
            });
        });
    </script>
@endpush
