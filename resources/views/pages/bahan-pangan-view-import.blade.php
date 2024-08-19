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
                <div class="col-12 mb-3">
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
                                                <td class="text-center">{{ $bahanPangan['kategori'] }}</td>
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
                <div class=col-md-12>
                    <button type="button" class="btn btn-primary" id="import_table"> Simpan </button>
                </div>
            @endif
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        $('#import_table').on('click', async (e) => {
            var data_pangan = @json($BahanPanganModel);
            await simpan(data_pangan, 0);
        });
        /*
        0,
        1,
        2
        */

        async function simpan(data_pangan, i) {
            let max = data_pangan.length - 1;
            if (i == max) {
                alert('Data sukses di update / ditambahkan.');
                window.location.replace("{{ route('bahan-pangan') }}");
                return;
            } else {
                var data, xhr;

                data = new FormData();
                data.append('nama_bahan', data_pangan[i]['nama_bahan']);
                data.append('kategori_id', data_pangan[i]['kategori_id']);
                data.append('bulan', data_pangan[i]['bulan']);
                data.append('tahun', data_pangan[i]['tahun']);
                data.append('harga', data_pangan[i]['harga']);

                await makeRequest(data);
                await simpan(data_pangan, i + 1);
            };
        }

        function makeRequest(data) {
            return new Promise(function(resolve, reject) {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('save-import') }}", true);
                xhr.setRequestHeader(
                    'X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content')
                );
                xhr.onload = function() {
                    if (this.status >= 200 && this.status < 300) {
                        resolve(xhr.response);
                    } else {
                        reject({
                            status: this.status,
                            statusText: xhr.statusText
                        });
                    }
                };
                xhr.onerror = function() {
                    reject({
                        status: this.status,
                        statusText: xhr.statusText
                    });
                };
                xhr.send(data);
            });
        }

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
