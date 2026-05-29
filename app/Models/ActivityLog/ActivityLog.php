<?php

namespace App\Models\ActivityLog;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        "id",
        "log_name",
        "description",
        "subject_type",		
		"subject_id",		
		"causer_type",		
		"causer_id",		
		"properties",		
		"created_at",		
		"updated_at",		
    ];
    
}


