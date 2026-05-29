<?php

namespace App\Models\Penawaran;

use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    protected $table = 't_penawaran';

    protected $primaryKey = 'penawaran_id';

    public $timestamps = false;
    protected $fillable = [
        "penawaran_id",
		"id_customer",
		"id_pejabat",
        "penawaran_nomor",
        "penawaran_hal",
        "penawaran_company",
		"penawaran_tgl",
        "penawaran_header",
        "penawaran_content",
        "penawaran_ttd",
		"penawaran_pejabat",
        "penawaran_hp",
		"penawaran_tahun",
        "user_record",
        "dt_record",
		"id_kelas",
        "nama",
        "dt_modified",
        "user_modified",
		"penawaran_pajak",
    ];
    
}

