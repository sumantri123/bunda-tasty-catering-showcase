<?php

namespace App\Models\TCPlanner;

use Illuminate\Database\Eloquent\Model;

class MCPlanner extends Model
{
    protected $table = 'm_kat_cplanner';

    protected $primaryKey = 'm_cplanner_id';

    public $timestamps = false;
    protected $fillable = [
        "m_cplanner_id",
        "m_cplanner_nama",
        "id_kelas",
        "badge_class",		
    ];
    
}


