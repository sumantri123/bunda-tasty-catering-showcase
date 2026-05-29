<?php

namespace App\Models\Pejabat;

use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    protected $table = 'm_pejabat';

    protected $primaryKey = 'pejabat_id';

    public $timestamps = false;
    protected $fillable = [
        "pejabat_id",
        "pejabat_nama",
        "pejabat_alamat",
        "pejabat_jabatan",
		"pejabat_telp",
		"pejabat_path",
		"pejabat_status",
		"pejabat_name_ori",
		"pejabat_name",
		"pejabat_exe",
		"pejabat_size",		
        "dt_record",
        "user_record",
        "dt_modified",
		"user_modified",
		"id_kelas",
    ];
    
}


