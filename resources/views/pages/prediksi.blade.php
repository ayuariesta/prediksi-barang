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
                    <form action="{{ route('prediksi-harga') }}" method="POST">
                        @csrf
                        <label for="nama_bahan">Pilih Nama Bahan:</label>
                        <select class="form-select" name="nama_bahan" id="nama_bahan">
                            @foreach ($data_bahan as $bahan)
                                <option value="{{ $bahan}}">{{ $bahan }}</option>
                            @endforeach
                        </select>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                        <br>
                        <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga Aktual</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">X</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">X<sup>2</sup></th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">X.Y</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $totalHarga = 0;
                                         $totalXpangkatDua = 0;
                                         $totalXy = 0;
                                         $totalX = 0;
                                         $dataA = 0;
                                         $dataB = 0;
                                         $lastX = 0;
                                         $yearLast = 0;
                                    @endphp
                                    @foreach ($tableData as $data)
                                            <tr class="text-center">
                                                <td>
                                                @php
                                                    $monthNames = [
                                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                    ];
                                                    $bulan = $monthNames[$data['bulan']];
                                                @endphp
                                                {{ $bulan }}
                                                </td>
                                                <td>{{ $data['tahun'] }}</td>
                                                <td>Rp {{ number_format($data['harga_aktual'], 0, ',', '.') }}</td>
                                                <td>{{ $data['x'] }}</td>
                                                <td>{{ $data['x_squared'] }}</td>
                                                <td>{{ $data['xy'] }}</td>
                                                @php 
                                                    $totalHarga += $data['harga_aktual'];
                                                    $totalXpangkatDua += $data['x_squared'];
                                                    $totalXy += $data['xy'];
                                                    $totalX += $data['x'];
                                                    $lastX = $data['x'];
                                                    $yearLast = $data['tahun'];
                                                @endphp
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="text-align: right;" colspan="2">Total</td>
                                            <td style="text-align: center;">{{number_format($totalHarga, 0, ',', '.')}}</td>
                                            <td></td>
                                            <td style="text-align: center;">{{$totalXpangkatDua}}</td>
                                            <td style="text-align: center;">{{$totalXy}}</td>
                                        </tr>
                                    </tfoot>
                                    @php 
                                         $dataA = $totalHarga / count($tableData);
                                         $dataB = $totalXy / $totalXpangkatDua;
                                    @endphp
                                </table>
                        </div>
                        <br>
                        <p class="mb-0 font-weight-bold">Nilai &Sigma;Y, &Sigma;X, &Sigma;X<sup>2</sup>, &Sigma;X.Y</p>
                        <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">&Sigma;Y</th>
                                            <th class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">&Sigma;X</th>
                                            <th class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">&Sigma;X<sup>2</sup></th>
                                            <th class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">&Sigma;X.Y</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                        <tr class="text-center">
                                            <td>{{$totalHarga}}</td>
                                            <td>{{$totalX}}</td>
                                            <td>{{$totalXpangkatDua}}</td>
                                            <td>{{$totalXy}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <th>&Sigma;Y</th>
                                            <th>&Sigma;X</th>
                                            <th>&Sigma;X<sup>2</sup></th>
                                            <th>&Sigma;X.Y</th>
                                        </tr>
                                    </tfoot>
                                </table>
                        </div>

                        <br><br>
                        <p class="mb-0 font-weight-bold">Nilai a dan Nilai b</p>
                        <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="font-weight-bold ">Nilai a</th>
                                            <th class="font-weight-bold ">Nilai b</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <th>{{$dataA}}</th>
                                            <th>{{$dataB}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                        <br><br>
                        <p class="mb-0 font-weight-bold">Perhitungan MAPE</p>
                        <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="font-weight-bold ">Bulan</th>
                                            <th class="font-weight-bold ">Tahun</th>
                                            <th class="font-weight-bold ">Harga Aktual</th>
                                            <th class="font-weight-bold ">Harga Prediksi</th>
                                            <th class="font-weight-bold ">|Y-Y'|/Y Error</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sumErrorYx = 0; @endphp
                                        @foreach ($tableData as $dataMape)
                                        <tr class="text-center">
                                            <td>
                                                 @php
                                                    $monthNames = [
                                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                    ];
                                                    $bulan = $monthNames[$dataMape['bulan']];
                                                @endphp
                                                {{ $bulan }}
                                            </td>
                                            <td>{{ $dataMape['tahun'] }}</td>
                                            <td>Rp {{ number_format($dataMape['harga_aktual'], 0, ',', '.') }}</td>
                                            <td>
                                                @php $prediksi = $dataA + ($dataB * $dataMape['x']); @endphp
                                                Rp {{ number_format($prediksi, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @php $erroYX = (abs($dataMape['harga_aktual'] - $prediksi)) / $dataMape['harga_aktual']; @endphp
                                                {{$erroYX}}
                                                @php $sumErrorYx += $erroYX; @endphp
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                        <br><br>
                         <p class="mb-0 font-weight-bold">MAPE</p>
                         <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="font-weight-bold">
                                            MAPE = SUM(|Y-Y'|/Y Error)/Total Data*100 | MAPE = {{$sumErrorYx}} / {{count($tableData)}} x 100
                                        </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <th>{{$sumErrorYx / count($tableData) * 100}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                        <br><br>
                        <p class="mb-0 font-weight-bold">Hasil Prediksi</p>
                        <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="font-weight-bold ">Bulan</th>
                                            <th class="font-weight-bold ">Tahun</th>
                                            <th class="font-weight-bold ">X</th>
                                            <th class="font-weight-bold ">Hasil Prediksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $median = count($tableData) / 2; 
                                            $monthNames = [
                                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                                    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                ];
                                        @endphp
                                        @foreach($monthNames as  $key => $item)
                                        <tr class="text-center">
                                            <td>{{$item}}</td>
                                            <td>{{$yearLast + 1}}</td>
                                            <td>
                                                @if($median % 2 == 1)
                                                    @php $lastX += 1; @endphp
                                                @else
                                                    @php $lastX += 2; @endphp
                                                @endif
                                                {{$lastX}}
                                            </td>
                                            <td>
                                                @php $prediksi = $dataA + ($dataB * $lastX); @endphp
                                                Rp {{ number_format($prediksi, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
