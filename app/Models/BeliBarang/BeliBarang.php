<?php

namespace App\Models\BeliBarang;

use Illuminate\Database\Eloquent\Model;

class BeliBarang extends Model
{
    protected $table = 'm_barang_keluar';

    protected $primaryKey = 'm_barang_keluar_id';

    public $timestamps = false;
    protected $fillable = [
        "m_barang_keluar_id",
        "m_barang_keluar_nama",
        "id_perkiraan",
        "id_lembaga",		
    ];
    
}


