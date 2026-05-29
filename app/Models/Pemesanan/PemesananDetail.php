<?php

namespace App\Models\Pemesanan;

use Illuminate\Database\Eloquent\Model;

class PemesananDetail extends Model
{
    protected $table = 't_pemesanan_detail';

    protected $primaryKey = 'pesan_detail_id';

    public $timestamps = false;
    protected $fillable = [
        "pesan_detail_id",
        "id_pesan",
		"id_perkiraan",
		"id_barang_keluar",
        "pesan_deskripsi",
        "qty",
		"satuan",
		"harga",
        "total",
        "pajak_nominal",
        "pajak_persen",
		"dt_record",
        "user_record",		   
    ];
    
}


