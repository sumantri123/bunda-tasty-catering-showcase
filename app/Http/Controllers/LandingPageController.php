<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sosmed\Sosmed;
use App\Models\Lembaga;
use Auth;
use Session;
use DB;
use nusoap_client;


class LandingPageController extends Controller
{

    // use AuthenticatesUsers;
    protected $redirectTo = '/';

	public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    public function index()
    {		
		$sosmed = Sosmed::where('id_kelas','=','57')->get();
		$lembaga = Lembaga::where('id','=','3')->get();		
        $data = array(			
			'sosmed' => $sosmed,
			'lembaga' => $lembaga,
        );       
						
		return view('landingpage/page/index',compact('data'));
    } 

	public function contact()
    {				
		$sosmed = Sosmed::where('id_kelas','=','57')->get();
		$lembaga = Lembaga::where('id','=','3')->get();		
        $data = array(	
			'sosmed' => $sosmed,
			'lembaga' => $lembaga,
        );       
						
		return view('landingpage/page/contact',compact('data'));
    }
}
