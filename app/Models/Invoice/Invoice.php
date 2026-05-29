<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 't_invoice';

    protected $primaryKey = 'invoice_id';

    public $timestamps = false;
    protected $fillable = [
        "invoice_id",
        "id_penawaran",
		"id_pejabat",
		"invoice_nomor",
        "invoce_tgl",
        "invoice_due_date",
		"invoice_po_nomor",
        "invoice_pajak_persen",
		"invoice_pejabat",
		"invoice_ttd",
		"invoice_tlp",
        "invoice_ke",
        "invoice_cetak",
		"invoice_tahun",
		"tipe_pembayaran",
		"id_kelas",
		"dt_record",
        "user_record",
		"dt_modified",        
		"user_modified",        
    ];
    
}

