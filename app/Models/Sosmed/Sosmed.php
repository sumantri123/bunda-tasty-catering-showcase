<?php

namespace App\Models\Sosmed;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Sosmed extends Model
{
	use LogsActivity;	
    protected $table = 'm_sosmed';
    protected $primaryKey = 'sosmed_id';

    public $timestamps = false;
    protected $fillable = [
        "sosmed_id",
        "sosmed_jenis",
        "sosmed_akun",
        "sosmed_link",
		"id_kelas",
        "publish",        
    ];

	protected static $logName = 'sosial media'; // untuk field logname
	protected static $logFillable = true; // untuk field properties
	protected static $logOnlyDirty = true; // yang tercatat hanya yang mengalami perubahan saja

	public function getDescriptionForEvent(string $eventName): string
    {
        return $this->sosmed_jenis . " {$eventName} by: " . Auth::user()->name;
    }
    
}


