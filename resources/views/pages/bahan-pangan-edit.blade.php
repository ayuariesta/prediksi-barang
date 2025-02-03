@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Bahan Pangan'])
    <div class="row mt-3 mx-3">
        <div class="col-14">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Bahan Pangan</h6>
                </div>
                <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_bahan" class="form-label">Nama Bahan</label><br>
                            <input type="text" class="form-control @error('nama_bahan') is-invalid @enderror" id="nama_bahan" name="nama_bahan" placeholder="Masukkan Nama Bahan" value="{{ $bahanpangan->nama_bahan }}" readonly>
                            @error('nama_bahan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label><br>
                                <select class="form-control @error('kategori_id') is-invalid @enderror" name="kategori_id" id="kategori_id" disabled>
                                    <option selected>Pilih Nama Kategori</option>
                                    @foreach ($kategori as $kategori)
                                    <option  value="{{ $kategori->id }}" {{ old('kategori_id', $bahanpangan->kategori_id) == $kategori->id ? 'selected' : null }}>{{ $kategori->nama_kategori }} </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="mb-3">
                            <label for="bulan" class="form-label">Bulan</label><br>
                            <input type="text" class="form-control @error('bulan') is-invalid @enderror" id="bulan" name="bulan" placeholder="Masukkan Bulan" value="{{ $bahanpangan->bulan }}" readonly>
                            @error('bulan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label><br>
                            <input type="text" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" placeholder="Masukkan Tahun" value="{{ $bahanpangan->tahun }}" readonly>
                            @error('tahun')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label><br>
                            <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" placeholder="Masukkan Harga" value="{{ $bahanpangan->harga }}" >
                            @error('harga')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    <br>
                    <br>

                    <div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{route('bahan-pangan')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection