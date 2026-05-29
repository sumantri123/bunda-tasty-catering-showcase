<?php

namespace App\Models\Delivery;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table = 't_delivery_order';

    protected $primaryKey = 'do_id';

    public $timestamps = false;
    protected $fillable = [
        "do_id",
        "do_nomor",
        "id_penawaran",
        "do_header",
		"do_tgl",
        "do_jenis",
		"do_tahun",
		"do_pejabat",
		"do_po_nomor",
		"do_ttd",
        "id_kelas",
        "dt_record",
		"user_record",
        "dt_modified",
		"user_modified",        
    ];
    
}

