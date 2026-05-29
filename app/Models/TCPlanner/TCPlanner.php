<?php

namespace App\Models\TCPlanner;

use Illuminate\Database\Eloquent\Model;

class TCPlanner extends Model
{
    protected $table = 't_cplanner';

    protected $primaryKey = 't_cplanner_id';

    public $timestamps = false;
    protected $fillable = [
        "t_cplanner_id",
        "id_m_kat_cplanner",
        "datestart",
        "jamstart",
		"detail",        
		"id_kelas",
		"id_sosmed",
    ];
    
}


