<?php

namespace App\Http\Controllers;

use App\Imports\BahanPanganImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BahanPangan;
use App\Models\Kategori;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Yajra\DataTables\DataTables;

class BahanPanganController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bahanpangan = BahanPangan::select(['id', 'nama_bahan', 'kategori_id', 'bulan', 'tahun', 'harga'])
                ->orderBy('tahun','desc')
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
                ->addColumn('action', function ($data) {
                    $button = ' <a href="' . route("bahan-edit", $data->id) . '" class="edit btn btn-success" id="' . $data->id . '" ><i class="fa fa-edit"></i></a>';
                    $button .= ' <a href="' . route("bahan-hapus", $data->id) . '" class="hapus btn btn-danger" id="' . $data->id . '"><i class="fa fa-trash"></i></a>';
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
        $bahanpangan->nama_bahan = $request->nama_bahan;
        $bahanpangan->bulan = $request->bulan;
        $bahanpangan->tahun = $request->tahun;
        $bahanpangan->harga = $request->harga;
        $bahanpangan->save();

        alert()->success('Bahan Pangan Berhasil Disimpan');
        return redirect('bahan-pangan');
    }

    public function edit($id)
    {

        $bahanpangan = BahanPangan::find($id);
        $kategori = Kategori::select('*')->get();
        return view('pages.bahan-pangan-edit', compact('bahanpangan', 'kategori'));
    }

    public function update(Request $request, $id)
    {

        $bahanpangan = BahanPangan::findOrFail($id);

        $bahanpangan->kategori_id = $request->kategori_id;
        $bahanpangan->nama_bahan = $request->nama_bahan;
        $bahanpangan->bulan = $request->bulan;
        $bahanpangan->tahun = $request->tahun;
        $bahanpangan->harga = $request->harga;
        $bahanpangan->update();
        alert()->success('Bahan Pangan Berhasil Diupdate', 'Success');
        return redirect('bahan-pangan');
    }

    public function save_update_pangan(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'nama_bahan' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'harga' => 'required',
        ], [
            'kategori_id.required' => 'Kategori tidak boleh kosong',
            'nama_bahan.required' => 'Nama Bahan tidak boleh kosong',
            'bulan.required' => 'Bulan tidak boleh kosong',
            'tahun.required' => 'Tahun tidak boleh kosong',
            'harga.required' => 'Harga tidak boleh kosong',
        ],
        );

        $arr_bulan = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];

        $bahanpangan = BahanPangan::where('nama_bahan', $request->nama_bahan)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('kategori_id', $request->kategori_id)
            ->updateOrCreate([
                'kategori_id' => $request->kategori_id,
                'nama_bahan' => $request->nama_bahan,
                'bulan' => $arr_bulan[$request->bulan],
                'tahun' => $request->tahun,
                'harga' => $request->harga,

            ]
            );

        // $bahanpangan = new BahanPangan;
        // $bahanpangan->kategori_id = $request->kategori_id;
        // $bahanpangan->nama_bahan = $request->nama_bahan;
        // $bahanpangan->bulan = $request->bulan;
        // $bahanpangan->tahun = $request->tahun;
        // $bahanpangan->harga = $request->harga;
        // $bahanpangan->save();

        echo json_encode(['is_success' => true, 'msg' => 'sukses']);
    }

    public function delete($id)
    {
        $data = BahanPangan::find($id);
        $data->delete();
        return redirect('bahan-pangan');
    }

    public function downloadSample()
    {
        $file = public_path() . "/docs/sample1.xlsx";

        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );

        return Response::download($file, 'sample1.xlsx', $headers);
    }
    public function importBahanPangan(Request $request)
    {
        try {
            $request->validate([
                'xlsx_file_bahan' => ['required', 'file', function ($attribute, $value, $fail) {
                    $allowedExtensions = ['xlsx', 'xls'];
                    $extension = $value->getClientOriginalExtension();

                    if (!in_array($extension, $allowedExtensions)) {
                        $fail("$attribute Harus ber ekstensi: " . implode(', ', $allowedExtensions) . '.');
                    }

                }],
            ]);

            $import = new BahanPanganImport;

            $file = $request->file('xlsx_file_bahan');

            Excel::import($import, $file->getRealPath(), null, \Maatwebsite\Excel\Excel::XLSX);

            $data = $import->getData();
            $sheet1 = $data[0];
            $headers = $sheet1[0];
            $headers_exp = [];
            for ($i = 2; $i < count($headers); $i++) {
                if($headers[$i] != null){
                    $exp_val = explode('-', $headers[$i]);
                    $headers_exp[] = [
                        'tahun' => $exp_val[1],
                        'bulan' => $exp_val[0]
                    ];
                }

            }
            // dd($headers_exp);
            $bahan_imp = [];
            for ($i = 1; $i < count($sheet1); $i++) {
                $ib_harga = [];
                for ($ib = 0; $ib <= count($sheet1[$i]); $ib++) {
                    if ($ib < count($headers_exp)) {
                        $ib_harga[] = array(
                            'tahun' => $headers_exp[$ib]['tahun'],
                            'bulan' => $headers_exp[$ib]['bulan'],
                            'harga' => $sheet1[$i][$ib + 2]
                        );
                    }
                }
                $bahan_imp[] = [
                    'kategori' => $sheet1[$i][0],
                    'nm_bahan' => $sheet1[$i][1],
                    'harga' => $ib_harga
                ];
            }


            $arr_bulan = [
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

            $BahanPanganModel = [];
            foreach ($bahan_imp as $key => $val) {
                foreach ($val['harga'] as $key2 => $val2) {
                    if (doubleval($val2['harga']) && !is_string($val2['harga']) && $val2['harga'] != 0) {
                        $BahanPanganModel[] = array(
                            'kategori' => $val['kategori'],
                            'kategori_id' => Kategori::where('nama_kategori', '=', $val['kategori'])->get('id')[0]['id'],
                            'nama_bahan' => $val['nm_bahan'],
                            'bulan' => $arr_bulan[$val2['bulan']],
                            'tahun' => $val2['tahun'],
                            'harga' => $val2['harga']
                        );
                    }

                }
            }
            // dd($BahanPanganModel);
            $error = null;
            return view('pages.bahan-pangan-view-import', compact('BahanPanganModel', 'error'));
        } catch (Exception $e) {
            //dd($e);
            $BahanPanganModel = [];
            $error = 'Pastikan Format yang di isikan sesuai dengan contoh.';
            return view('pages.bahan-pangan-view-import', compact('BahanPanganModel', 'error'));
        }
    }
}
