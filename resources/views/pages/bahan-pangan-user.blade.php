@extends('layouts.app-user', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.guest.topnav', ['title' => 'Bahan Pangan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Bahan Pangan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped yajra-datatable" id="pangan-table">
                                <thead>
                                    <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Bahan Pangan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bulan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tahun</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.guest.footer')
    </div>
@endsection
@push('js')
<script type="text/javascript">
  $(function() {
    var table = $('#pangan-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 10,
                language:{
                    "url":"https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
                },
                ajax: '{!! route('bahan-pangan-user') !!}', // memanggil route yang menampilkan data json
                columns: [{ // mengambil & menampilkan kolom sesuai tabel database
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        sClass:'text-center'
                    },
                    {
                        data: 'nama_bahan',
                        name: 'nama_bahan',
                        sClass:'text-center'
                    },
                    {
                        data: 'kategori.nama_kategori',
                        name: 'kategori.nama_kategori',
                        searchable: true,
                        sClass:'text-center'
                    },
                    {
                        data: 'bulan',
                        searchable: false,
                        sClass:'text-center'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        searchable: false,
                        sClass:'text-center'
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                        searchable: false,
                        sClass:'text-center',
                        render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp ' )
                    },
                ],
            });
});
</script>
@endpush