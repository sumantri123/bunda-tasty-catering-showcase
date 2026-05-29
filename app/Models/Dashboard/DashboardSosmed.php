<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;

class DashboardSosmed extends Model
{
    protected $table = 't_dashboard_sosmed';

    protected $primaryKey = 't_ds_id';

    public $timestamps = false;
    protected $fillable = [
        "t_ds_id",
        "id_sosmed",
        "follower",
        "following",
		"likes",
        "content",
		"open_id",
		"nama_tampilan",
		"date",
		"desc",
		"avatar",
		"profile_link",		
    ];
    
}

