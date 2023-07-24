@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Kategori'])
    <div class="row mt-3 mx-3">
        <div class="col-14">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Kategori</h6>
                </div>
                <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label><br>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori" name="nama_kategori" placeholder="Masukkan Nama Kategori" value="{{ $kategori->nama_kategori }}">
                            @error('nama_kategori')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    <br>
                    <br>

                    <div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{route('kategori')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection