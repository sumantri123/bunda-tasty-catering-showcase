<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\BukaTransaksi;
use Session;
use Auth;

class RoleOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {	//date('Y-m-d', strtotime($request->tgl)),
        $tanggal = date('Y-m-d', strtotime($request->input('tgl')));
		$cekData = BukaTransaksi::where([
						['buka_tanggal','=',$tanggal],
						['buka_aktif','=','y'],
						['id_kelas','=',Session::get('kelas')],
					])->count();
        if(($cekData)>0){
            return $next($request);
        }else{
			return response()->json(['status'=>'insert_failed','msg'=>' Tanggal masih belum dibuka untuk transaksi ']);
		}
    }
}
