<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BahanPangan;
use App\Models\Kategori;
use Auth;
use Yajra\DataTables\DataTables;

class HargaBahanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bahanpangan = BahanPangan::select(['id','nama_bahan','kategori_id','bulan','tahun','harga'])
                    ->with(['kategori']);

            return Datatables::of($bahanpangan)
                ->addIndexColumn()
                ->addColumn('bulan', function ($data) {
                    $monthNames = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ];
                    return $monthNames[$data->bulan];
                })
                ->make(true);
        }

        $kategori = Kategori::select('*')->get();
        return view('pages.bahan-pangan-user', compact('kategori'));
    }
}
