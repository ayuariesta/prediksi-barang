<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanPangan;
use Yajra\DataTables\DataTables;

class PrediksiUserController extends Controller
{
    public function form(Request $request)
    {

        $data_bahan = BahanPangan::select('nama_bahan')->distinct()->pluck('nama_bahan');
        $tableData = [];

        return view('pages.prediksi-user', ['data_bahan' => $data_bahan, 'request' => $request, 'tableData' => $tableData]);

    }

    public function prediksiHarga(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string',
            'start_range_bahan' => 'required|string',
            'end_range_bahan' => 'required|string',
        ]);

        $param_wheres = [
            'nama_bahan' => $request->input('nama_bahan'),
            'start_waktu' => $request->input('start_range_bahan'),
            'end_waktu' => $request->input('end_range_bahan')
        ];

        $tableData = $this->calculateValues($param_wheres);
        $tableData = $tableData['data'];
        $data_bahan = BahanPangan::select('nama_bahan')->distinct()->pluck('nama_bahan');
        return view('pages.prediksi-user', compact('param_wheres', 'data_bahan', 'tableData', 'request'));
    }

    public function calculateValues($params)
    {
        $exp_start = explode('-', $params['start_waktu']);
        $exp_end = explode('-', $params['end_waktu']);
        $tahun_start = (int) $exp_start[0];
        $bulan_start = (int) $exp_start[1];
        $tahun_end = (int) $exp_end[0];
        $bulan_end = (int) $exp_end[1];
        // Retrieve data from your database or data source based on the selected nama_bahan
        $dataHistori = BahanPangan::where('nama_bahan', $params['nama_bahan'])
            ->where(function ($query) use ($tahun_start, $bulan_start, $tahun_end, $bulan_end) {
                if ($tahun_start == $tahun_end) {
                    // Jika start dan end pada tahun yang sama
                    $query->where('tahun', $tahun_start)
                        ->whereBetween('bulan', [$bulan_start, $bulan_end]);
                } else {
                    // Jika rentang waktu mencakup lebih dari satu tahun
                    $query->where(function ($query) use ($tahun_start, $bulan_start) {
                        $query->where('tahun', $tahun_start)
                            ->where('bulan', '>=', $bulan_start);
                    })
                        ->orWhere(function ($query) use ($tahun_end, $bulan_end) {
                        $query->where('tahun', $tahun_end)
                            ->where('bulan', '<=', $bulan_end);
                    })
                        ->orWhereBetween('tahun', [$tahun_start + 1, $tahun_end - 1]);
                }
            })
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc');
        $dataHistori = $dataHistori->get();
        $n = count($dataHistori);
        $xValues = [];
        $xSquaredValues = [];
        $xyValues = [];

        $median = $n / 2;

        $dataHistori = json_decode(json_encode($dataHistori), true);
        $tableData = [];
        //ganjil
        //dd($median);
        if ($median % 2 == 1) {
            $start = round($median);
            $xAtas = -1;
            //atas
            for ($i = 0; $i <= $start; $i++) {
                $index = $start - $i; // loop keatas
                if ($i == 0) {
                    $xAtas = 0;
                } else {
                    $xAtas += -1;
                }
                $dataHistori[$index]['x'] = $xAtas;
                $square = pow($xAtas, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xAtas * @$dataHistori[$index]['harga'] ?? 0;
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = @$dataHistori[$index]['harga'] ?? 0;
            }
            //bawah
            $xBawah = -1;
            for ($i = 0; $i <= $start - 1; $i++) {
                $index = $start + $i; // loop kebawah
                if ($i == 0) {
                    $xBawah = 1;
                } else {
                    $xBawah += 1;
                }
                $dataHistori[$index]['x'] = $xBawah;
                $square = pow($xBawah, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xBawah * @$dataHistori[$index]['harga'] ?? 0;
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = @$dataHistori[$index]['harga'] ?? 0;
            }
        } else //genap
        {
            $start = $n - $median;
            $xAtas = -1;
            //atas
            for ($i = 0; $i <= $start; $i++) {
                $index = $start - $i; // loop keatas
                if ($i == 0) {
                    $xAtas = 1;
                } else {
                    $xAtas += -2;
                }
                $dataHistori[$index]['x'] = $xAtas;
                $square = pow($xAtas, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xAtas * @$dataHistori[$index]['harga'] ?? 0;
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = @$dataHistori[$index]['harga'] ?? 0;
            }
            //bawah
            $xBawah = -1;
            for ($i = 0; $i <= $start - 1; $i++) {
                $index = $start + $i; // loop kebawah
                if ($i == 0) {
                    $xBawah = 1;
                } else {
                    $xBawah += 2;
                }
                $dataHistori[$index]['x'] = $xBawah;
                $square = pow($xBawah, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xBawah * @$dataHistori[$index]['harga'] ?? 0;
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = @$dataHistori[$index]['harga'] ?? 0;
            }
        }
        $arr = [];
        $arr['data'] = $dataHistori;
        return $arr;
    }

    public function getStartnEndDateBahan(Request $request)
    {
        if ($request->method() == "GET") {
            return redirect('/');
        }
        $request->validate([
            'nama_bahan' => 'required|string',
        ]);

        $nama_bahan = $request->input('nama_bahan');
        $start_month = BahanPangan::select(\DB::raw('MIN(bulan) AS MonthStart, MIN(tahun) AS YearStart,Max(bulan) AS MonthEnd, Max(tahun) AS YearEnd'))->where('nama_bahan', $nama_bahan)->get();
        // dd($start_month[0]->MonthStart);
        $range_bahan = [
            'month' => [
                'start' => $start_month[0]->MonthStart ?? 0,
                'end' => $start_month[0]->MonthEnd ?? 0
            ],
            'year' => [
                'start' => $start_month[0]->YearStart ?? (int) Date('Y'),
                'end' => $start_month[0]->YearEnd ?? (int) Date('Y')
            ],
        ];
        return $range_bahan;

    }
}
