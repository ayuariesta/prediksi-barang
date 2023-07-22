<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BahanPangan;
use App\Models\Kategori;
use Auth;
use Yajra\DataTables\DataTables;

class BahanPanganController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bahanpangan = BahanPangan::select(['id','nama_bahan','kategori_id','bulan','tahun','harga'])
                    ->with(['kategori']);

            return Datatables::of($bahanpangan)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = ' <a href="#" class="edit btn btn-success" id="getEditKategoriData" data-id="'.$data->id.'" ><i class="fa fa-edit"></i></a>';
                    $button .= ' <a href="'. route("bahan-hapus", $data->id).'" class="hapus btn btn-danger" id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $kategori = Kategori::select('*')->get();
        return view('pages.bahan-pangan', compact('kategori'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
        ], [
            'kategori_id.required' => 'Kategori tidak boleh kosong'
        ]);

            $bahanpangan = new BahanPangan;
            $bahanpangan->kategori_id = $request->kategori_id;
            $bahanpangan->nama_bahan= $request->nama_bahan;
            $bahanpangan->bulan = $request->bulan;
            $bahanpangan->tahun = $request->tahun;
            $bahanpangan->harga = $request->harga;
            $bahanpangan->save();

        alert()->success('Bahan Pangan Berhasil Disimpan');
        return redirect('bahan-pangan');
    }

    public function delete($id)
    {
        $data = BahanPangan::find($id);
        $data->delete();
        return redirect('bahan-pangan');
    }
}
