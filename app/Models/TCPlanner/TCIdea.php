<?php

namespace App\Models\TCPlanner;

use Illuminate\Database\Eloquent\Model;

class TCIdea extends Model
{
    protected $table = 't_cidea';

    protected $primaryKey = 't_cidea_id';

    public $timestamps = false;
    protected $fillable = [
        "t_cidea_id",
        "deskripsi",
        "url_inspirasi",
        "status",
		"pic",        
		"tenggat_waktu",
		"url_file",
		"id_kelas",
		"id_sosmed",
    ];
    
}


