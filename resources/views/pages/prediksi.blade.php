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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_bahan">Pilih Nama Bahan:</label>
                                    <select class="form-select" name="nama_bahan" id="nama_bahan">
                                        @foreach ($data_bahan as $bahan)
                                            <option {{ @$param_wheres['nama_bahan'] == $bahan ? 'selected' : '' }}
                                                value="{{ $bahan }}">{{ $bahan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $tahun = date('Y');
                                @endphp
                                <div class="row">
                                    <label for="start_range_bahan">Rentang Waktu:</label>
                                    <div class="col-md-6 mb-1">
                                        <div class="row">
                                            <div class="col-md-4 text-center align-content-center">
                                                <input class="form-control range-bahan" type="month" id="start_range_bahan"
                                                    name="start_range_bahan"
                                                    value="{{ @$param_wheres['start_waktu'] ?? $tahun . '-01' }}" />
                                            </div>
                                            <div class="col-md-2 text-center align-content-center">s/d</div>
                                            <div class="col-md-4 text-center align-content-center">
                                                <input class="form-control range-bahan" type="month" id="end_range_bahan"
                                                    name="end_range_bahan"
                                                    value="{{ @$param_wheres['end_waktu'] ?? $tahun . '-12' }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        @if (isset($nama_bahan))
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Prediksi Bahan Pangan dengan Nama : {{ $nama_bahan }}</p>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Silahkan Pilih Nama Bahan</p>
                            </div>
                        @endif
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Bulan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tahun</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Harga Aktual</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">X
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            X<sup>2</sup></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">X.Y
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalHarga = 0;
                                        $totalXpangkatDua = 0;
                                        $totalXy = 0;
                                        $totalX = 0;
                                        $dataA = 0;
                                        $dataB = 0;
                                        $lastX = 0;
                                        $monthLast = 0;
                                        $yearLast = 0;
                                    @endphp
                                    @foreach ($tableData as $data)
                                        @if (@$data['bulan'])
                                            <tr class="text-center">
                                                <td>
                                                    @php
                                                        $monthNames = [
                                                            1 => 'Januari',
                                                            2 => 'Februari',
                                                            3 => 'Maret',
                                                            4 => 'April',
                                                            5 => 'Mei',
                                                            6 => 'Juni',
                                                            7 => 'Juli',
                                                            8 => 'Agustus',
                                                            9 => 'September',
                                                            10 => 'Oktober',
                                                            11 => 'November',
                                                            12 => 'Desember',
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
                                                    $monthLast = $data['bulan'];
                                                @endphp
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align: right;" colspan="2">Total</td>
                                        <td style="text-align: center;">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                                        <td></td>
                                        <td style="text-align: center;">{{ $totalXpangkatDua }}</td>
                                        <td style="text-align: center;">{{ $totalXy }}</td>
                                    </tr>
                                </tfoot>
                                @if (count($request->all()) > 0)
                                    @php
                                        $dataA = $totalHarga / count($tableData);
                                        $dataB = $totalXy / $totalXpangkatDua;
                                    @endphp
                                @endif
                            </table>
                        </div>
                        <br>
                        <p class="mb-0 font-weight-bold">Nilai &Sigma;Y, &Sigma;X, &Sigma;X<sup>2</sup>, &Sigma;X.Y</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th
                                            class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">
                                            &Sigma;Y</th>
                                        <th
                                            class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">
                                            &Sigma;X</th>
                                        <th
                                            class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">
                                            &Sigma;X<sup>2</sup></th>
                                        <th
                                            class="font-weight-bold text-uppercase text-secondary text-m font-weight-bolder">
                                            &Sigma;X.Y</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td>{{ $totalHarga }}</td>
                                        <td>{{ $totalX }}</td>
                                        <td>{{ $totalXpangkatDua }}</td>
                                        <td>{{ $totalXy }}</td>
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
                                        <th>{{ $dataA }}</th>
                                        <th>{{ $dataB }}</th>
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
                                        @if (@$dataMape['bulan'])
                                            <tr class="text-center">
                                                <td>
                                                    @php
                                                        $monthNames = [
                                                            1 => 'Januari',
                                                            2 => 'Februari',
                                                            3 => 'Maret',
                                                            4 => 'April',
                                                            5 => 'Mei',
                                                            6 => 'Juni',
                                                            7 => 'Juli',
                                                            8 => 'Agustus',
                                                            9 => 'September',
                                                            10 => 'Oktober',
                                                            11 => 'November',
                                                            12 => 'Desember',
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
                                                    {{ $erroYX }}
                                                    @php $sumErrorYx += $erroYX; @endphp
                                                </td>
                                            </tr>
                                        @endif
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
                                            @if (count($request->all()) > 0)
                                                MAPE = SUM(|Y-Y'|/Y Error)/Total Data*100 | MAPE = {{ $sumErrorYx }} /
                                                {{ count($tableData) }} x 100
                                            @endif
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        @if (count($request->all()) > 0)
                                            <th>{{ ($sumErrorYx / count($tableData)) * 100 }}</th>
                                        @endif
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
                                    @if (count($request->all()) > 0)
                                        @php
                                            $median = count($tableData) / 2;
                                            $monthNames = [
                                                1 => 'Januari',
                                                2 => 'Februari',
                                                3 => 'Maret',
                                                4 => 'April',
                                                5 => 'Mei',
                                                6 => 'Juni',
                                                7 => 'Juli',
                                                8 => 'Agustus',
                                                9 => 'September',
                                                10 => 'Oktober',
                                                11 => 'November',
                                                12 => 'Desember',
                                            ];

                                            $lastMonth = $monthLast;
                                            $lastYear = $yearLast;

                                            $startMonth = $lastMonth + 1;
                                            $startYear = $lastYear;
                                            if ($startMonth > 12) {
                                                $startMonth = 1;
                                                $startYear += 1;
                                            }
                                        @endphp
                                        @for ($i = 0; $i < 12; $i++)
                                            <tr class="text-center">
                                                <td>{{ $monthNames[$startMonth] }}</td>
                                                <td>{{ $startYear }}</td>
                                                <td>
                                                    @if ($median % 2 == 1)
                                                        @php $lastX += 1; @endphp
                                                    @else
                                                        @php $lastX += 2; @endphp
                                                    @endif
                                                    {{ $lastX }}
                                                </td>
                                                <td>
                                                    @php $prediksi = $dataA + ($dataB * $lastX); @endphp
                                                    Rp {{ number_format($prediksi, 0, ',', '.') }}
                                                </td>
                                            </tr>

                                            @php
                                                // Pindah ke bulan berikutnya
                                                $startMonth++;
                                                if ($startMonth > 12) {
                                                    $startMonth = 1;
                                                    $startYear++;
                                                }
                                            @endphp
                                        @endfor
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            initRangeWaktuBahan();

            $('#nama_bahan').on('change', function() {
                initRangeWaktuBahan();
            })

            $('.range-bahan').on('change', function() {
                let start_momenth = moment($('#start_range_bahan').val());
                let end_momenth = moment($('#end_range_bahan').val());
                if (end_momenth.diff(start_momenth, 'days') < 0) {
                    $('#start_range_bahan').val($('#end_range_bahan').val())
                }
            });

            function initRangeWaktuBahan() {
                try {
                    $.post("api/rentang-waktu-bahan", {
                            nama_bahan: $('#nama_bahan').val(),
                        },
                        function(data, status) {
                            console.log(data);
                            let start_waktu = data.year.start.toString().padStart(4, '0') + '-' + data.month.start
                                .toString().padStart(2, "0");
                            let end_waktu = data.year.end.toString().padStart(4, '0') + '-' + data.month.end.toString()
                                .padStart(2, "0");
                            $('#start_range_bahan').attr('min', start_waktu);
                            $('#start_range_bahan').attr('max', end_waktu);
                            // $('#start_range_bahan').val(start_waktu);

                            $('#end_range_bahan').attr('min', start_waktu);
                            $('#end_range_bahan').attr('max', end_waktu);
                            // $('#end_range_bahan').val(end_waktu);
                        });
                } catch (err) {
                    console.log(err);
                }
            }
        </script>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
