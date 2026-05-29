<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'm_supplier';

    protected $primaryKey = 'supplier_id';

    public $timestamps = false;
    protected $fillable = [
        "supplier_id",
        "supplier_nama",
        "supplier_alamat",
        "supplier_pejabat",
		"supplier_telp",
        "dt_record",
        "user_record",
        "dt_modified",
		"user_modified",
		"id_kelas",
    ];
    
}


