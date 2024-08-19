<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BahanPanganImport implements ToCollection
{
    public $data = [];
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $this->data[] = $collection;
    }
    public function getData()
    {
        return $this->data;
    }
}
