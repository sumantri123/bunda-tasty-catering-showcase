<?php

namespace App\Models\Penawaran;

use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    protected $table = 't_dok_po';

    protected $primaryKey = 'po_id';

    public $timestamps = false;
    protected $fillable = [
        "po_id",
        "id_penawaran",
		"id_invoice",
		"id_pesan",
        "file_name",
        "file_path",
		"id_kelas",
		"id_jenis_file",
		"id_kw",
        "file_name_ori",
        "file_exe",
        "file_size",		
        "user_record",
        "dt_record",		
        "dt_modified",
        "user_modified",		
    ];
    
}

