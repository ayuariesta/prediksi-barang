@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Kategori'])
    <div class="row mt-3 mx-3">
        <div class="col-14">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Kategori</h6>
                    <br>
                        <a href="{{route('kategori-create')}}" class="btn btn-sm btn-success" id="tambah-kategori" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</a>
                    <br>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped yajra-datatable" id="kategori-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Kategori</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('kategori-save')}}" enctype="multipart/form-data">
                        @csrf
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label><br>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Masukkan Nama Kategori">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
<script type="text/javascript">
  $(function() {
    var table = $('#kategori-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                language:{
                    "url":"https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
                },
                ajax: '{!! route('kategori') !!}', // memanggil route yang menampilkan data json
                columns: [{ // mengambil & menampilkan kolom sesuai tabel database
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        sClass:'text-center'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori',
                        sClass:'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true,
                        sClass:'text-center'
                    }
                ]
    });
});
</script>
<script>
  $(document).on('click','.hapus', function (e) {
    e.preventDefault();
    const href = $(this).attr('href');
    Swal.fire({
      title: 'Apakah anda yakin menghapus data ini?',
		  text: "Data yang dihapus tidak bisa dikembalikan!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Hapus Data!'
        }).then((result) => {
		  if (result.value) {
        Swal.fire(
          'Deleted!',
          'Your file has been deleted.',
          'success'
        )
        document.location.href = href; //kembalikan nilai true dengan redirect document ke halaman yang dituju
  		}
    })
  });
</script>
@endpush