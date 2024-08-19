<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class BahanPangan extends Model
{
    use HasFactory;

    protected $table = 'bahan_pangan';
    protected $fillable = ['nama_bahan', 'tahun', 'bulan', 'harga','kategori_id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

}

