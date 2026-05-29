<?php

namespace App\Models\Sosmed;

use Illuminate\Database\Eloquent\Model;

class SosmedToko extends Model
{
    protected $table = 'sosmed_toko';

    protected $primaryKey = 'sosmed_toko_id';

    public $timestamps = false;
    protected $fillable = [
        "sosmed_toko_id",
        "id_sosmed",
        "sosmed_toko_akun",
        "sosmed_toko_link",
		"id_kelas",				
        "publish",        
    ];
    
}


