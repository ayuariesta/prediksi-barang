@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Kelola Admin'])
    <div class="row mt-3 mx-3">
        <div class="col-14">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Data Admin</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('kelola-user.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label><br>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" value="{{ old('username', $user->username) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label><br>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label><br>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password Minimal 6 karakter" >
                    </div>
                    <div>
                        <input type="checkbox" id="showPassword" onclick="togglePassword()">
                        <label for="showPassword">Show Password</label>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password">
                    </div>
                    <div>
                        <input type="checkbox" id="showPassword1" onclick="togglePassword1()">
                        <label for="showPassword1">Show Password Konfirmasi</label>
                    </div>
                    <br>
                    <br>

                    <div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }

    function togglePassword1() {
        const passwordField = document.getElementById('password_confirmation');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
</script>
@endpush