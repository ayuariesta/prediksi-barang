@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Bahan Pangan'])
    <div class="container-fluid py-4">
        <div class="row">
            @if ($error != null)
                <div class=col-12>
                    <div class="card mb-4">
                        <div class="card-body">
                            {{ $error }}
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped yajra-datatable" id="pangan-table">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Nama Bahan Pangan</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Nama Kategori</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Bulan</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Tahun</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($BahanPanganModel as $bahanPangan)
                                            <tr>
                                                <td class="text-center">{{ $bahanPangan['nama_bahan'] }}</td>
                                                <td class="text-center">{{ $bahanPangan['kategori_id'] }}</td>
                                                <td class="text-center">{{ $bahanPangan['bulan'] }}</td>
                                                <td class="text-center">{{ $bahanPangan['tahun'] }}</td>
                                                <td class="text-center">{{ $bahanPangan['harga'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        $('#download_file_sample').on('click', (e) => {
            e.preventDefault();
            window.location.href = "/download-sample";
        });
        $(function() {
            var table = $('#pangan-table').DataTable({
                processing: false,
                serverSide: false,
                autoWidth: false,
                language: {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
                },
            });
        });
    </script>
@endpush
