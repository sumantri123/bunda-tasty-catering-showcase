<?php

namespace App\Models\Delivery;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderDetail extends Model
{
    protected $table = 't_delivery_order_det';

    protected $primaryKey = 'do_det_id';

    public $timestamps = false;
    protected $fillable = [
        "do_det_id",
        "id_do",
        "do_deskripsi",
        "do_keterangan",
		"qty",
        "do_satuan",
		"do_tahun",
		"dt_record",
		"user_record",        
    ];
    
}

