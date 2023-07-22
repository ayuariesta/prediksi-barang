@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Kategori'])
    <div class="row mt-3 mx-3">
        <div class="col-14">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Kategori</h6>
                    <br>
                        <a href="#" class="btn btn-sm btn-success" id="tambah-kategori" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</a>
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

        @include('layouts.footers.auth.footer')
    </div>
@endsection