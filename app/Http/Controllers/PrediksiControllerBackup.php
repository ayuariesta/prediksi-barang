<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BahanPangan;
use Yajra\DataTables\DataTables;

class PrediksiController extends Controller
{
    public function form()
    {

        $data_bahan = BahanPangan::select('nama_bahan')->distinct()->pluck('nama_bahan');
        $tableData = [];

        return view('pages.prediksi', ['data_bahan' => $data_bahan, 'tableData' => $tableData]);

    }

    public function prediksiHarga(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string',
        ]);

        $nama_bahan = $request->input('nama_bahan');

        $tableData = $this->calculateValues($nama_bahan);

        $data_bahan = BahanPangan::select('nama_bahan')->distinct()->pluck('nama_bahan');
        return view('pages.prediksi', compact('nama_bahan', 'data_bahan', 'tableData'));
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

        $median = $n % 2 == 0 ? $n / 2 : ($n + 1) / 2;

        $dataHistori = json_decode(json_encode($dataHistori),true);
        // dd($dataHistori[30]);
        //ganjil
        if ($n % 2 === 1) {
            for ($i = 0; $i <= $n; $i++) {
                $xValue = $i - $median;
                $xValues[] = $xValue;
                $xSquaredValues[] = pow($xValue, 2);
                $xyValues[] = $xValue * $dataHistori[$i]->harga;
            }
        }
        //genap
        else {
            foreach ($dataHistori as $i => $data) {
                $xValue = ($i + ($i + 1)) / 2 - $median;
                $xValues[] = $xValue;
                $xSquaredValues[] = pow($xValue, 2);
                $xyValues[] = $xValue * $data->harga;
            }
        }

        // Combine the $xValues, $xSquaredValues, and $xyValues arrays to be displayed in the table
        $tableData = [];
        foreach ($dataHistori as $index => $data) {
            $tableData[] = [
                'bulan' => $data->bulan,
                'tahun' => $data->tahun,
                'harga_aktual' => $data->harga,
                'x' => $xValues[$index],
                'x_squared' => $xSquaredValues[$index],
                'xy' => $xyValues[$index],
            ];
        }
        return $tableData;
    }
}
