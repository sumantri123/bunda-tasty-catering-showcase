<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

// uji coba log activity menggunakan spatie/laravel-activitylog:^3.17
class Customer extends Model
{
	use LogsActivity;	
    protected $table = 'm_customer';
    protected $primaryKey = 'customer_id';

    public $timestamps = false;
    protected $fillable = [
        "customer_id",
        "customer_nama",
        "customer_alamat",
        "customer_pejabat",
		"customer_telp",
        "dt_record",
        "user_record",
        "dt_modified",
		"user_modified",
		"id_kelas",
    ];

    protected static $logName = 'customer'; // untuk field logname
	protected static $logFillable = true; // untuk field properties
	protected static $logOnlyDirty = true; // yang tercatat hanya yang mengalami perubahan saja

	public function getDescriptionForEvent(string $eventName): string
    {
        return $this->customer_nama . " {$eventName} by: " . Auth::user()->name;
    }
}


