@extends('layouts.app')

@section('title', 'Input Nomor Rangka dan Mesin')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Input Nomor Rangka dan Mesin</h1>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Stock: {{ $stocks->count() }} Unit</h4>
                        <div class="card-header-action">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" id="showAll">Semua Data</button>
                                <button type="button" class="btn btn-info" id="showFilled">Sudah Diisi</button>
                                <button type="button" class="btn btn-warning" id="showEmpty">Belum Diisi</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stock.saveNomor') }}" method="POST" id="stockForm">
                            @csrf
                            @foreach ($stocks as $index => $stock)
                                <div class="border rounded p-3 mb-4 shadow-sm stock-item" 
                                    data-filled="{{ (!empty($stock->no_rangka) && !empty($stock->no_mesin)) ? 'true' : 'false' }}">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-motorcycle fa-2x mr-2 text-primary"></i>
                                                <div>
                                                    <h6 class="mb-0">{{ $stock->pembelian_detail->master_motor->nama_motor }}</h6>
                                                    <small class="text-muted">{{ $stock->master_warna->nama_warna }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="custom-control custom-switch float-right">
                                                <input type="checkbox" class="custom-control-input" 
                                                    id="custom_awalan_{{ $stock->id }}"
                                                    onchange="toggleCustomAwalan({{ $stock->id }})">
                                                <label class="custom-control-label" for="custom_awalan_{{ $stock->id }}">
                                                    Gunakan awalan kustom
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="d-block">
                                                    <i class="fas fa-barcode mr-1"></i>
                                                    Nomor Rangka:
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light">
                                                            {{ $stock->pembelian_detail->master_motor->nomor_rangka }}
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control stock-input"
                                                        id="no_rangka_{{ $stock->id }}"
                                                        name="stocks[{{ $index }}][no_rangka]"
                                                        value="{{ old('stocks.' . $index . '.no_rangka', substr($stock->no_rangka, strlen($stock->pembelian_detail->master_motor->nomor_rangka))) }}"
                                                        maxlength="{{ 15 - strlen($stock->pembelian_detail->master_motor->nomor_rangka) }}"
                                                        placeholder="Masukkan nomor rangka">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="d-block">
                                                    <i class="fas fa-engine mr-1"></i>
                                                    Nomor Mesin:
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light">
                                                            {{ $stock->pembelian_detail->master_motor->nomor_mesin }}
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control stock-input"
                                                        id="no_mesin_{{ $stock->id }}"
                                                        name="stocks[{{ $index }}][no_mesin]"
                                                        value="{{ old('stocks.' . $index . '.no_mesin', substr($stock->no_mesin, strlen($stock->pembelian_detail->master_motor->nomor_mesin))) }}"
                                                        maxlength="{{ 15 - strlen($stock->pembelian_detail->master_motor->nomor_mesin) }}"
                                                        placeholder="Masukkan nomor mesin">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row custom-awalan-{{ $stock->id }}" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Awalan Nomor Rangka Kustom:
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="custom_awalan_rangka_{{ $stock->id }}"
                                                    name="stocks[{{ $index }}][custom_awalan_rangka]"
                                                    value="{{ old('stocks.' . $index . '.custom_awalan_rangka') }}"
                                                    maxlength="14"
                                                    onchange="updateMaxLength({{ $stock->id }}, 'rangka')"
                                                    placeholder="Masukkan awalan kustom">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Awalan Nomor Mesin Kustom:
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="custom_awalan_mesin_{{ $stock->id }}"
                                                    name="stocks[{{ $index }}][custom_awalan_mesin]"
                                                    value="{{ old('stocks.' . $index . '.custom_awalan_mesin') }}"
                                                    maxlength="14"
                                                    onchange="updateMaxLength({{ $stock->id }}, 'mesin')"
                                                    placeholder="Masukkan awalan kustom">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="stocks[{{ $index }}][id]" value="{{ $stock->id }}">
                                </div>
                            @endforeach

                            <div class="form-group text-right">
                                <a href="{{ route('stock.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .border {
            transition: all 0.3s ease;
        }
        .border:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
        }
        .input-group-text {
            min-width: 100px;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function toggleCustomAwalan(id) {
            const customAwalanDiv = document.querySelector(`.custom-awalan-${id}`);
            const checkbox = document.getElementById(`custom_awalan_${id}`);
            const noRangkaInput = document.getElementById(`no_rangka_${id}`);
            const noMesinInput = document.getElementById(`no_mesin_${id}`);
            const defaultAwalanRangka = '{{ $stock->pembelian_detail->master_motor->nomor_rangka }}';
            const defaultAwalanMesin = '{{ $stock->pembelian_detail->master_motor->nomor_mesin }}';

            customAwalanDiv.style.display = checkbox.checked ? 'flex' : 'none';
            noRangkaInput.maxLength = checkbox.checked ? 15 : 15 - defaultAwalanRangka.length;
            noMesinInput.maxLength = checkbox.checked ? 15 : 15 - defaultAwalanMesin.length;
        }

        function updateMaxLength(id, type) {
            const input = document.getElementById(`no_${type}_${id}`);
            const customAwalanInput = document.getElementById(`custom_awalan_${type}_${id}`);
            input.maxLength = Math.max(15 - customAwalanInput.value.length, 0);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('stockForm');
            const submitBtn = document.getElementById('submitBtn');
            const inputs = document.querySelectorAll('.stock-input');

            function validateForm() {
                const isValid = Array.from(inputs).some(input => {
                    const motorId = input.id.split('_').pop();
                    const rangkaInput = document.getElementById(`no_rangka_${motorId}`);
                    const mesinInput = document.getElementById(`no_mesin_${motorId}`);
                    return rangkaInput.value.trim() !== '' && mesinInput.value.trim() !== '';
                });

                submitBtn.disabled = !isValid;
            }

            inputs.forEach(input => input.addEventListener('input', validateForm));

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Menyimpan Data',
                    text: 'Apakah Anda yakin ingin menyimpan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
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
                            url: $(this).attr('action'),
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data nomor rangka dan mesin berhasil disimpan.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('stock.index') }}';
                                });
                            },
                            error: function(xhr) {
                                let errorMessage =
                                    'Terjadi kesalahan saat menyimpan data.';
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    errorMessage = Object.values(xhr.responseJSON
                                        .errors).join('\n');
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });

            validateForm();
        });

        $(document).ready(function() {
            // Filter buttons functionality
            $('#showAll').click(function() {
                $('.stock-item').show();
                $(this).addClass('btn-primary').removeClass('btn-outline-primary');
                $('#showFilled, #showEmpty').addClass('btn-outline-primary').removeClass('btn-primary btn-info btn-warning');
            });

            $('#showFilled').click(function() {
                $('.stock-item').hide();
                $('.stock-item[data-filled="true"]').show();
                $(this).addClass('btn-info').removeClass('btn-outline-info');
                $('#showAll, #showEmpty').addClass('btn-outline-primary').removeClass('btn-primary btn-info btn-warning');
            });

            $('#showEmpty').click(function() {
                $('.stock-item').hide();
                $('.stock-item[data-filled="false"]').show();
                $(this).addClass('btn-warning').removeClass('btn-outline-warning');
                $('#showAll, #showFilled').addClass('btn-outline-primary').removeClass('btn-primary btn-info btn-warning');
            });

            // Set initial state
            $('#showAll').click();

            // Update data-filled attribute when inputs change
            $('.stock-input').on('input', function() {
                const stockItem = $(this).closest('.stock-item');
                const noRangka = stockItem.find('input[id^="no_rangka_"]').val();
                const noMesin = stockItem.find('input[id^="no_mesin_"]').val();
                stockItem.attr('data-filled', (noRangka && noMesin) ? 'true' : 'false');
            });
        });
    </script>
@endpush
