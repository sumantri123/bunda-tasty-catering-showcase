<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 't_invoice_det';

    protected $primaryKey = 'invoice_det_id';

    public $timestamps = false;
    protected $fillable = [
        "invoice_det_id",
        "id_invoice",
		"invoice_deskripsi",
        "qty",
        "harga",
		"total",
        "pajak_nominal",
		"pajak_persen",
		"dt_record",
        "user_record",
    ];
    
}

