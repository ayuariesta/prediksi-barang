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

        return view('pages.prediksi-user', ['data_bahan' => $data_bahan,'request'=>$request, 'tableData' => $tableData]);

    }

    public function prediksiHarga(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string',
        ]);

        $nama_bahan = $request->input('nama_bahan');

        $tableData = $this->calculateValues($nama_bahan);
        $tableData = $tableData['data'];
        $data_bahan = BahanPangan::select('nama_bahan')->distinct()->pluck('nama_bahan');
        return view('pages.prediksi-user', compact('nama_bahan', 'data_bahan', 'tableData','request'));
    }

    public function calculateValues($nama_bahan)
    {
        // Retrieve data from your database or data source based on the selected nama_bahan
        $dataHistori = BahanPangan::where('nama_bahan', $nama_bahan)
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
        $n = count($dataHistori);
        $xValues = [];
        $xSquaredValues = [];
        $xyValues = [];

        $median = $n / 2;

        $dataHistori = json_decode(json_encode($dataHistori),true);
        $tableData = [];
        //ganjil
        //dd($median);
        if($median % 2 == 1)
        {
            $start = round($median);
            $xAtas = -1;
            //atas
            for ($i=0; $i <= $start; $i++) 
            { 
                $index = $start - $i; // loop keatas
                if($i == 0)
                {
                    $xAtas = 0;
                }else
                {
                    $xAtas += -1;
                }
                $dataHistori[$index]['x'] = $xAtas;
                $square = pow($xAtas, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xAtas * $dataHistori[$index]['harga'];
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = $dataHistori[$index]['harga'];
            }
            //bawah
            $xBawah = -1;
            for ($i=0; $i <= $start - 1; $i++) 
            { 
                $index = $start + $i; // loop kebawah
                if($i == 0)
                {
                    $xBawah = 1;
                }else
                {
                    $xBawah += 1;
                }
                $dataHistori[$index]['x'] = $xBawah;
                $square = pow($xBawah, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xBawah * $dataHistori[$index]['harga'];
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = $dataHistori[$index]['harga'];
            }
        }else //genap
        {
            $start = $n - $median;
            $xAtas = -1;
            //atas
            for ($i=0; $i <= $start; $i++) 
            { 
                $index = $start - $i; // loop keatas
                if($i == 0)
                {
                    $xAtas = 1;
                }else
                {
                    $xAtas += -2;
                }
                $dataHistori[$index]['x'] = $xAtas;
                $square = pow($xAtas, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xAtas * $dataHistori[$index]['harga'];
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = $dataHistori[$index]['harga'];
            }
            //bawah
            $xBawah = -1;
            for ($i=0; $i <= $start - 1; $i++) 
            { 
                $index = $start + $i; // loop kebawah
                if($i == 0)
                {
                    $xBawah = 1;
                }else
                {
                    $xBawah += 2;
                }
                $dataHistori[$index]['x'] = $xBawah;
                $square = pow($xBawah, 2);
                $dataHistori[$index]['x_squared'] = $square;
                $xy = $xBawah * $dataHistori[$index]['harga'];
                $dataHistori[$index]['xy'] = $xy;
                $dataHistori[$index]['harga_aktual'] = $dataHistori[$index]['harga'];
            }
        }
        $arr = [];
        $arr['data'] = $dataHistori;
        return $arr;
    }
}
