<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BahanPangan;

class PrediksiController extends Controller
{
    public function form()
    {

        $data_bahan = BahanPangan::select('nama_bahan')->distinct()->pluck('nama_bahan');

        return view('pages.prediksi', ['data_bahan' => $data_bahan]);
    }

    public function prediksiHarga(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string',
        ]);

        $nama_bahan = $request->input('nama_bahan');

        $dataHistori = BahanPangan::where('nama_bahan', $nama_bahan)
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get(['harga', 'tahun', 'bulan']);

        $n = count($dataHistori);
        $xValues = [];
        $yValues = [];

        foreach ($dataHistori as $data) {
            $xValues[] = ($data->tahun - $dataHistori[0]->tahun) * 12 + $data->bulan;
            $yValues[] = $data->harga;
        }

        $xSum = array_sum($xValues);
        $ySum = array_sum($yValues);
        $xySum = 0;
        $xSquaredSum = 0;

        foreach ($xValues as $index => $x) {
            $xySum += $x * $yValues[$index];
            $xSquaredSum += pow($x, 2);
        }

        if($n > 0 && $xySum > 0 && $xSum > 0 && $ySum > 0 && $xSquaredSum > 0 ){
            $a = ($n * $xySum - $xSum * $ySum) / ($n * $xSquaredSum - pow($xSum, 2));
            $b = ($ySum - $a * $xSum) / $n;
        }
        $predictions = [];
        $latestData = $dataHistori->last();
        $latestYear = $latestData->tahun;
        $latestMonth = $latestData->bulan;

        for ($i = 1; $i <= 12; $i++) {
            $predictedMonth = ($latestYear - $dataHistori[0]->tahun) * 12 + $latestMonth + $i;
            $predictedYear = floor(($predictedMonth - 1) / 12) + $dataHistori[0]->tahun;
            $predictedMonth = ($predictedMonth - 1) % 12 + 1;

            $predictedPrice = $a * $predictedMonth + $b;
            $predictions[] = [
                'tahun' => $predictedYear,
                'bulan' => $predictedMonth,
                'predicted_price' => $predictedPrice,
            ];
        }

        return view('pages.prediksi', ['nama_bahan' => $nama_bahan, 'prediksi' => $predictions]);
    }
}
