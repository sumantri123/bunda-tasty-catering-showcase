<?php

namespace App\Models\Kwitansi;

use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    protected $table = 't_kwitansi';

    protected $primaryKey = 'kw_id';

    public $timestamps = false;
    protected $fillable = [
        "kw_id",
        "id_invoice",
		"id_customer",
		"kw_tgl",
        "kw_company",
        "kw_deskripsi",
		"kw_terbilang",
        "kw_nominal",
		"kw_pajak_persen",
		"kw_pajak_nominal",
		"kw_nomor",
        "kw_ttd",
        "user_record",
		"dt_record",
		"kw_tahun",
		"user_modified",
		"dt_modified",
        "id_kelas",		
    ];
    
}

