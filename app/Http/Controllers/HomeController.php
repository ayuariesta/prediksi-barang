<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.dashboard');
    }

    public function index1()
    {
        return view('pages.dashboard-user');
    }

    public function getAvailableYears()
    {
        $years = DB::table('bahan_pangan')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun');

        return response()->json($years);
    }

    public function getChartData(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y') // Validasi tahun
        ]);

        $year = $request->input('year'); // Ambil input tahun dari request

        $data = DB::table('bahan_pangan')
            ->join('kategori', 'bahan_pangan.kategori_id', '=', 'kategori.id')
            ->select('kategori.nama_kategori', DB::raw('AVG(bahan_pangan.harga) as rata_rata'))
            ->where('bahan_pangan.tahun', $year)
            ->groupBy('kategori.nama_kategori')
            ->get();

        return response()->json($data);
    }
}
