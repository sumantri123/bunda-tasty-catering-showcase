<?php

namespace App\Models\Penawaran;

use Illuminate\Database\Eloquent\Model;

class PenawaranDetail extends Model
{
    protected $table = 't_penawaran_detail';

    protected $primaryKey = 'penawaran_detail_id';

    public $timestamps = false;
    protected $fillable = [
        "penawaran_detail_id",
        "id_penawaran",
		"id_perkiraan",
        "penawaran_deskripsi",
        "qty",
		"harga",
        "total",
        "pajak_nominal",
        "pajak_persen",
		"dt_record",
		"user_record",
		"id_menu",
    ];
    
}

