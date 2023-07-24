@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Prediksi'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-14">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Tabel Prediksi</p>
                        </div>
                    </div>
                    <div class="card-body">
                    <form action="{{ route('prediksiHarga') }}" method="post">
                        @csrf
                        <label for="nama_bahan">Pilih Nama Bahan:</label>
                        <select class="form-select" aria-label="Default select example" name="nama_bahan" id="nama_bahan">
                            @foreach ($data_bahan as $nama_bahan => $data_bahan)
                                <option value="{{ $nama_bahan }}">{{ $data_bahan }}</option>
                            @endforeach
                        </select>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="kategori-table">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bulan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tahun</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">x</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">x<sup>2</sup></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">x.y</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        @if (isset($predictions))
                            <h2>Prediksi Harga untuk {{ $nama_bahan }}</h2>
                            <table class="table-responsive">
                                <thead class="table table-bordered table-striped">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prediksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($predictions as $prediction)
                                        <tr>
                                            <td>{{ $prediction['bulan'] }}</td>
                                            <td>{{ $prediction['tahun'] }}</td>
                                            <td>{{ $prediction['predicted_price'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
