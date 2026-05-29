<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\User;
use App\Models\Role;
use App\Models\BukaTransaksi;
use App\Models\EditPerkiraan;
use App\Models\Dashboard\DashboardSosmed;
use Auth;
use Session;
use DB;
use Hash;
use Validator;
use Carbon\Carbon;

class LoginController extends Controller
{

    // use AuthenticatesUsers;
    protected $redirectTo = '/';

	public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'login_as']]);
		$this->yearNow = Carbon::now()->year;
		$this->date = Carbon::now()->format('m/d/Y');
		$this->when = Carbon::now();
    }

    public function showLoginForm(Request $request)
    {		
		$password = Hash::make('12345678');

		//echo $password;
        if($request->session()->has('batas_waktu') && Carbon::now()<$request->session()->get('batas_waktu')) {
            
            $wkt_skrg = $request->session()->get('waktu_skrg');
            $batas_waktu = $request->session()->get('batas_waktu');
            $real_now = Carbon::now();
            $button_disabled = 1;
            return view('login', compact('button_disabled', 'batas_waktu', 'wkt_skrg', 'real_now'));
        }else{
            if($request->session()->has('batas_waktu') || $request->session()->has('waktu_skrg')) {
                $request->session()->forget('batas_waktu');
                $request->session()->forget('waktu_skrg');
                $request->session()->forget('error_login');
            }

            if($request->session()->has('error_login')){
                $jumlah_error_login = $request->session()->get('error_login');
            }else{
                $jumlah_error_login = 0;
			}
		    
			$lembaga = DB::table('m_lembaga')->where('domain', '=', $_SERVER['HTTP_HOST'])->get();
			
			if(isset($lembaga[0])){
				$kelas = DB::table('m_kelas')->where('status_kelas','=','y')->where('id_lembaga', '=',$lembaga[0]->id)->get();
				echo '<script>localStorage.removeItem("menu");</script>';
				return view('login', compact('jumlah_error_login', 'kelas', 'lembaga'));
			}else{
				die('access_denied');
			}
        } 
 
    }

    public function adminLoginForm(Request $request)
    {

        if($request->session()->has('batas_waktu') && Carbon::now()<$request->session()->get('batas_waktu')) {

            $wkt_skrg = $request->session()->get('waktu_skrg');
            $batas_waktu = $request->session()->get('batas_waktu');
            $real_now = Carbon::now();
            $button_disabled = 1;
            return view('admin', compact('button_disabled', 'batas_waktu', 'wkt_skrg', 'real_now'));

        }else{

            if($request->session()->has('batas_waktu') || $request->session()->has('waktu_skrg')) {
                $request->session()->forget('batas_waktu');
                $request->session()->forget('waktu_skrg');
                $request->session()->forget('error_login');
            }

            if($request->session()->has('error_login')){
                $jumlah_error_login = $request->session()->get('error_login');
            }else{
                $jumlah_error_login = 0;
	   		}

	    	$lembaga = DB::table('m_lembaga')->where('domain', '=', $_SERVER['HTTP_HOST'])->get();
	    	
			if(isset($lembaga[0])){
				$kelas = DB::table('m_kelas')->where('status_kelas','=','y')->where('id_lembaga', '=',$lembaga[0]->id)->get();

				return view('admin', compact('jumlah_error_login', 'kelas', 'lembaga'));
			}else{
			//die('access_denied');
			}
        }

    }
	
    public function getDataKelas(Request $request)
	{

		$lembaga = DB::table('m_lembaga')->where('domain', '=', $_SERVER['HTTP_HOST'])->get();


		if(isset($lembaga[0])){
            $kelas = DB::table('m_kelas')->where('status_kelas','=','y')->where('id_lembaga', '=',$lembaga[0]->id)->get();
		
		if(isset($kelas)){
			$html_kelas = '<select class="form-select" id="kelas" aria-label="Default select example">';
			 $html_kelas .= '<option value=0>Pilih Kelas</option>';
			foreach($kelas as $data_kelas){
			 $html_kelas .= '<option value='.$data_kelas->id.'>'.$data_kelas->name.'</option>';
			}
		}else{
			$html_kelas = 'Kelas Belum Disetup';
		}
		return response()->json([
		            'status'=>'successful',
		            'data_kelas' => $html_kelas
		]);
		}else{
			 return response()->json([
                            'status'=>'failed',
                            'data_kelas' => $html_kelas
	                ]);
		}
	}

	public function getDataUserKelas(Request $request)
    {
		$users = DB::table('users')
                ->where('kelas_id', '=', $request->id_kelas)
                ->get();
		
		if(isset($users)){
			$html_users = '<select class="form-select" id="user_kelas" aria-label="Default select example">';
			foreach($users as $data_users){
			 $html_users .= '<option value='.$data_users->username.'>'.$data_users->name.'</option>';
			}
			$html_users .= '</select>';
		}else{
			$html_users = 'Kelas Belum Disetup';
		}
		return response()->json([
            'status'=>'successful',
            'data_users' => $html_users
        ]);
	}
    public function logout(Request $request)
    {
		
		/* 1. Cabut Akses Token */
		$headers = [	
			'Content-Type: application/x-www-form-urlencoded',		
			'Cache-Control: no-cache',						
		];

		$payload = [
			'client_key' => 'awrz7lfk05zf4pm2',
			'client_secret' => '3faefa0a360a920b848a98d705e75a8d',
			'token'   => Session::get('accessToken'),			
		];			

		$url = "https://open.tiktokapis.com/v2/oauth/revoke/";		

		$client = curl_init();
		curl_setopt($client, CURLOPT_URL, $url);
		curl_setopt($client, CURLOPT_HTTPHEADER, $headers); 				
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($payload));

		$response = curl_exec($client);
		$result = json_decode($response);		

        $this->guard('web')->logout();
				
		$request->session()->flush();
        $request->session()->regenerate();
        return redirect('/auth');
    }

    public function admin_login(Request $request)
    {
		$jumlah_error_login = 0;
		$this->validate($request, [
			'password'   => 'required',
		]);

    	$pass = $request->password;
		$user = $request->username;
				
		$lembaga = DB::table('m_lembaga')
				->where('domain', '=', $_SERVER['HTTP_HOST'])
				->where('user_login_admin', '=', $user)				
				->get();		
		
		$ambil = User::where('username','=','admin')
			->where('kelas_id', '=', '57')
			->first();

		if((count($lembaga) > 0) && (Hash::check($pass,$lembaga[0]->pass_admin_login))){

			Auth::login($ambil);
			Session::put('idLembaga',$lembaga[0]->id);
			Session::put('username',$lembaga[0]->user_login_admin);
			Session::put('name',$lembaga[0]->user_login_admin);			
			Session::put('tanggal',Carbon::now()->isoFormat('D MMMM Y'));
			Session::put('subtitle',$lembaga[0]->nama_lembaga);
			Session::put('alamat',$lembaga[0]->alamat_lembaga);
			Session::put('passAdmin',$lembaga[0]->pass_admin);
			Session::put('logoHeaderTransaksi',$lembaga[0]->logo_header);
			Session::put('logoUHW',$lembaga[0]->logo_login);
			Session::put('logoSidebar',$lembaga[0]->logo_sidebar);
			Session::put('login_as','IT');

			return response()->json([
				'status'=>'insert_successful'
			]);

		}else{
			
			Session::put('username',$request->user_kelas);
			return response()->json([
				'status'=>'insert_failed_password',
			]);
		}
    }

    public function login(Request $request)
    {
		$jumlah_error_login = 0;
		$this->validate($request, [
			'user_kelas'    => 'required',
			'password'   => 'required',
		]);
        
        $pass = $request->password;
		$ambil = User::where('username','=',$request->user_kelas)				
				->first();
		
            if($ambil ){

                if(Hash::check($pass,$ambil->password)){

					$dataLembaga = DB::table('m_kelas as a')
					->leftJoin('m_lembaga as b', 'a.id_lembaga', '=', 'b.id')            
					->select('a.*', 'b.*')            					
					->where('a.id','=',$ambil->kelas_id)					
					->get();	
					
					$dataPerkiraan = DB::select(                                
						DB::raw("
								select id, kode_perkiraan, nama_perkiraan, kode_otomatis
								from m_perkiraan 
								where id_lembaga =".$dataLembaga[0]->id_lembaga."
								and right(kode_otomatis,3) <> 'XXX'
								and right(kode_otomatis,3) <> '000'
							")
					);  
					
					//var_dump($dataPerkiraan);					
					foreach($dataPerkiraan as $row){
						Session::put($row->kode_otomatis,$row->kode_perkiraan);
						Session::put($row->kode_otomatis.'_ID',$row->id);
					}

					Auth::login($ambil);
					Session::put('username',$request->user_kelas);
					Session::put('name',$ambil->name);
					Session::put('avatar',$dataLembaga[0]->logo_login);
					Session::put('usrId',$ambil->user_id);
					Session::put('password',$request->password);
					Session::put('alamat',$dataLembaga[0]->alamat_lembaga);
					Session::put('telp',$dataLembaga[0]->telp_lembaga);
					Session::put('kelas',$ambil->kelas_id);
					Session::put('tanggal',$request->tanggal);
					Session::put('subtitle',$dataLembaga[0]->nama_bank);
					Session::put('passAdmin',$dataLembaga[0]->pass_admin);
					Session::put('idLembaga',$dataLembaga[0]->id_lembaga);
					Session::put('logoHeaderTransaksi',$dataLembaga[0]->logo_header);
					Session::put('logoUHW',$dataLembaga[0]->logo_login);
					Session::put('logoSidebar',$dataLembaga[0]->logo_sidebar);
					Session::put('login_as',$ambil->name);
					Session::put('accessToken','');
					Session::put('openId','');
					
					return response()->json([
						'status'=>'insert_successful'
					]);


                }else{

					Session::put('username',$request->user_kelas);					
					return response()->json([
						'status'=>'insert_failed_password',
					]);
				}

            }else{
				return response()->json([
						'status'=>'insert_failed_email',
					]);
			}

    }
	
	public function login_bytiktok(Request $request)
    {		
	
		if(isset($_GET['code'])){
			/* 1. GET token From Tiktok */
			$code = $_GET['code'];
			
			/* $scope = $_GET['scopes'];
			$state = $_GET['state'];	 */	
			$headers = [	
				'Content-Type: application/x-www-form-urlencoded',		
				'Cache-Control: no-cache',						
			];
			
			$payload = [
				'client_key' => 'awrz7lfk05zf4pm2',
				'client_secret' => '3faefa0a360a920b848a98d705e75a8d',
				'code'   => $code,
				'grant_type'   => 'authorization_code',
				'redirect_uri'   => 'https://siricing.perbanas.ac.id/authCallback'
			];			

			$url = "https://open.tiktokapis.com/v2/oauth/token/";		

			$client = curl_init();
			curl_setopt($client, CURLOPT_URL, $url);
			curl_setopt($client, CURLOPT_HTTPHEADER, $headers); 				
			curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($payload));

			$response = curl_exec($client);
			$result = json_decode($response);		
			
			$accessToken = $result->access_token;
			$openId = $result->open_id;
			$refreshToken = $result->refresh_token;
			$scope = $result->scope;
			$tipeToken = $result->token_type;
			
			
			/* 2. GET Info basic From Tiktok */
			$code = $_GET['code'];				
			$headers1 = [	
				'Authorization: Bearer '.$accessToken,								
			];				

			$url1 = "https://open.tiktokapis.com/v2/user/info/?fields=open_id,username,union_id,avatar_url,display_name,profile_deep_link,bio_description,video_count,likes_count,follower_count,following_count,";		

			$client1 = curl_init();
			curl_setopt($client1, CURLOPT_URL, $url1);
			curl_setopt($client1, CURLOPT_HTTPHEADER, $headers1); 				
			curl_setopt($client1, CURLOPT_RETURNTRANSFER, 1);		

			$response1 = curl_exec($client1);
			$result1 = json_decode($response1);
			
			$openId = $result1->data->user->open_id;
			$displayName = $result1->data->user->display_name;
			$avatar = $result1->data->user->avatar_url;
			$follower = $result1->data->user->follower_count;	
			$following = $result1->data->user->following_count;	
			$like = $result1->data->user->likes_count;	
			$desc = $result1->data->user->bio_description;	
			$videoCount = $result1->data->user->video_count;	
			$profileLink = $result1->data->user->profile_deep_link;	
			$userNameTiktok = $result1->data->user->username;	

			if($accessToken){
				
				$ambil = User::where('username_tiktok','=',$userNameTiktok)->first();				
				$dataLembaga = DB::table('m_lembaga')
						->where('domain', '=', $_SERVER['HTTP_HOST'])						
						->get();
										
				if($ambil){
					
					$dataPerkiraan = DB::select(                                
						DB::raw("
								select id, kode_perkiraan, nama_perkiraan, kode_otomatis
								from m_perkiraan 
								where id_lembaga =".$dataLembaga[0]->id."
								and right(kode_otomatis,3) <> 'XXX'
								and right(kode_otomatis,3) <> '000'
							")
					);  
					
					//var_dump($dataPerkiraan);					
					foreach($dataPerkiraan as $row){
						Session::put($row->kode_otomatis,$row->kode_perkiraan);
						Session::put($row->kode_otomatis.'_ID',$row->id);
					}


					Auth::login($ambil);
					Session::put('username',$request->user_kelas);
					Session::put('name',$displayName);
					Session::put('avatar',$avatar);
					Session::put('usrId',$ambil->user_id);
					Session::put('password','');
					Session::put('alamat',$dataLembaga[0]->alamat_lembaga);
					Session::put('telp',$dataLembaga[0]->telp_lembaga);
					Session::put('kelas',$ambil->kelas_id);
					Session::put('tanggal',$request->tanggal);
					Session::put('subtitle',$dataLembaga[0]->nama_bank);
					Session::put('passAdmin',$dataLembaga[0]->pass_admin);
					Session::put('idLembaga',$dataLembaga[0]->id);
					Session::put('logoHeaderTransaksi',$dataLembaga[0]->logo_header);
					Session::put('logoUHW',$dataLembaga[0]->logo_login);
					Session::put('logoSidebar',$dataLembaga[0]->logo_sidebar);
					Session::put('login_as',$displayName);
					Session::put('accessToken',$accessToken);
					Session::put('openId',$openId);
					
					
					
					// Save di tabel dashboard sosmed untuk pembuatan grafik per bulan
					try {
						
						$insert = DashboardSosmed::create([						
									"id_sosmed"=> 4,				
									"follower"=> $follower,
									"following"=> $following,
									"likes"=> $like,
									"content"=> $videoCount,
									"open_id"=> $openId,
									"nama_tampilan"=> $displayName,
									"date"=> $this->when,
									"desc"=> $desc,
									"avatar"=> $avatar,
									"profile_link"=> $profileLink,
								]);
								
						if($insert) {
							DB::commit();
							return redirect()->route('dashboard');	
						} else {
							return response()->json(['status'=>'insert_failed']);
						}
						
					} catch (\Throwable $e) {

						DB::rollback();            
						throw $e;            
						return response()->json(['status'=>'insert_failed']);

					}
					
				} else {
					
					//Jangan dihapus, untuk membatasi agar yang masuk hanya tiktok bunda tasty catering
					//return redirect()->route('login2')->with('message', 'User Anda Tidak Terdaftar Dalam Akun Kami, Silahkan Hubungi Administrator!');
					
					
					$ambil = User::where('username','=','percobaan')->first();
					
					$dataPerkiraan = DB::select(                                
						DB::raw("
								select id, kode_perkiraan, nama_perkiraan, kode_otomatis
								from m_perkiraan 
								where id_lembaga =".$dataLembaga[0]->id."
								and right(kode_otomatis,3) <> 'XXX'
								and right(kode_otomatis,3) <> '000'
							")
					);  
														
					foreach($dataPerkiraan as $row){
						Session::put($row->kode_otomatis,$row->kode_perkiraan);
						Session::put($row->kode_otomatis.'_ID',$row->id);
					}


					Auth::login($ambil);
					Session::put('username',$request->user_kelas);
					Session::put('name',$displayName);
					Session::put('avatar',$avatar);
					Session::put('usrId',$ambil->user_id);
					Session::put('password','');
					Session::put('alamat',$dataLembaga[0]->alamat_lembaga);
					Session::put('telp',$dataLembaga[0]->telp_lembaga);
					Session::put('kelas',$ambil->kelas_id);
					Session::put('tanggal',$request->tanggal);
					Session::put('subtitle',$dataLembaga[0]->nama_bank);
					Session::put('passAdmin',$dataLembaga[0]->pass_admin);
					Session::put('idLembaga',$dataLembaga[0]->id);
					Session::put('logoHeaderTransaksi',$dataLembaga[0]->logo_header);
					Session::put('logoUHW',$dataLembaga[0]->logo_login);
					Session::put('logoSidebar',$dataLembaga[0]->logo_sidebar);
					Session::put('login_as',$displayName);
					Session::put('accessToken',$accessToken);
					Session::put('openId',$openId);					
					
					// Save di tabel dashboard sosmed untuk pembuatan grafik per bulan
					try {
						
						$insert = DashboardSosmed::create([						
									"id_sosmed"=> 4,				
									"follower"=> $follower,
									"following"=> $following,
									"likes"=> $like,
									"content"=> $videoCount,
									"open_id"=> $openId,
									"nama_tampilan"=> $displayName,
									"date"=> $this->when,
									"desc"=> $desc,
									"avatar"=> $avatar,
									"profile_link"=> $profileLink,
								]);
								
						if($insert) {
							DB::commit();
							return redirect()->route('dashboard');	
						} else {
							return response()->json(['status'=>'insert_failed']);
						}
						
					} catch (\Throwable $e) {

						DB::rollback();            
						throw $e;            
						return response()->json(['status'=>'insert_failed']);

					}
					
				}
										
			}else{
				
				return response()->json([
						'status'=>'failed',
						'msg'=>'Anda Gagal Terhubung Dengan Tiktok',
					]);
			}
			
		} else {
			
			header('Location: https://siricing.perbanas.ac.id/auth');	
			die();
		}

    }
	
	public function login_as(Request $request)
    {
		
		
		if($request->sebagai == 'EDP'){
			if(Session::get('name')=="EDP"){
				Session::put('login_as','EDP');
				return response()->json([
					'status'=>'insert_successful',
				]);

			}else{
				return response()->json([
					'login_as'=>'failed_edp',
				]);
			}
		}else{
			Session::put('login_as',$request->sebagai);
			return response()->json([
					'status'=>'insert_successful',
			]);

		}
    }

	public function terms_condition(Request $request)
    {
		return view('terms/index');		
		
    }

	public function privacy_policy(Request $request)
    {
		return view('terms/privacy_policy');		
		
    }

	
    protected function guard()
    {
        return Auth::guard('web');
    }

}
