<?php

namespace App\Exports;

use App\BahanPangan;
use Maatwebsite\Excel\Concerns\FromCollection;

class BahanPanganExport implements FromCollection
{
    public function collection()
    {   
        return BahanPangan::all();
    }
}