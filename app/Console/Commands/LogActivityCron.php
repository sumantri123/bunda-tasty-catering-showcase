<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog\ActivityLog;
use App\Models\Customer\Customer;
use Session;

class LogActivityCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logactivity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        		
		Log::info("Cron job Berhasil di jalankan " . date('Y-m-d H:i:s'));
		/* $month = 9;
		$query = ActivityLog::whereMonth('created_at', '=', $month)->delete(); */

		/* $insert = Customer::create([
                    "customer_nama"=> "1",
                    "customer_alamat"=> "2",
					"id_kelas"=> 57,
                    "user_record"=> 'admin',
                    "customer_pejabat"=> "3",
					"customer_telp"=> "4",
                    "dt_record"=> date("Y-m-d H:i:s")
                ]); */
		
    }
}
