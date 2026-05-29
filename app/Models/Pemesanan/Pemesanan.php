<?php

namespace App\Models\Pemesanan;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 't_pemesanan';

    protected $primaryKey = 'pesan_id';

    public $timestamps = false;
    protected $fillable = [
        "pesan_id",
        "id_supplier",
		"id_penawaran",
        "pesan_tgl",
        "pesan_hal",		
        "pesan_tahun",
		"pesan_nomor",
        "pesan_pajak",
        "id_kelas",
		"dt_record",
        "user_record",
		"dt_modified",
        "user_modified",        
    ];
    
}


