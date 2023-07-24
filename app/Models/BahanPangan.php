<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class BahanPangan extends Model
{
    use HasFactory;

    public $table = 'bahan_pangan';
    protected $fillable = ['nama_bahan', 'tahun', 'bulan', 'harga'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}

