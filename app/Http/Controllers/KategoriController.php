<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use Auth;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = ' <a href="'. route("kategori-edit", $data->id).'" class="edit btn btn-success " id="' . $data->id . '" ><i class="fa fa-edit"></i></a>';
                    $button .= ' <a href="'. route("kategori-hapus", $data->id).'" class="hapus btn btn-danger" id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.kategori');
    }

    public function save(Request $request)
    {
        $kategori = new Kategori;
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();
        alert()->success('Kategori Berhasil Disimpan');
        return redirect('kategori');
    }

    public function edit($id)
    {

        $kategori = Kategori::find($id);
        return view('pages.billing',compact('kategori'));
    }

    public function update(Request $request, $id)
    {

        $kategori = Kategori::findOrFail($id);

        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->update();
        alert()->success('Data Kategori Berhasil Diupdate', 'Success');
        return redirect('kategori');
    }

    public function delete($id)
    {
        $data = Kategori::find($id);
        $data->delete();
        return redirect('kategori');
    }
}
