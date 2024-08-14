@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Kelola User'])
    <div class="row mt-3 mx-3">
        <div class="col-14">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Kelola User</h6>
                </div>
                <div class="card-body">
                        <form action="{{ route('user-update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label><br>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Masukkan username" value="{{ old('username', $user->username) }}">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label><br>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password baru" >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    <br>
                    <br>

                    <div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection