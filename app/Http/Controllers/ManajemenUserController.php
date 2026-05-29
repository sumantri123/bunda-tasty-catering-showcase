<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Kelas;
use Auth;
use Session;
use DB;
use Hash;

class ManajemenUserController extends Controller
{

    // use AuthenticatesUsers;
    protected $redirectTo = '/';

	public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    public function index()
    {		
		
        $data = array(
			'title' => 'Manajemen User',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
			'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
			'classFormSelect2' => 'single-select',
        );       
			
		$kelas = Kelas::get();		
	    $returnHTML = view('man_user/index',compact('data','kelas'))->render();
	    return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
		
        $cs = DB::select(
					DB::raw('
						SELECT a.*, b.name as grup_kelas
						FROM users as a		
						left join m_kelas as b on a.kelas_id = b.id
						ORDER BY grup_kelas asc
					')
				);

        //if($cs) {
            return response()->json([
                'status'=>'oke',
                'data' => $cs
                ]);
        /* } else {
            return response()->json(['status'=>'failed']);
        } */

    }
    
    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
            "kode_perkiraan" => "nama",            
        ], $messages);
    }
	
	public function store(Request $request)
    {
        if($request->ajax()){
          
            DB::beginTransaction();

            try {
                $insert = User::create([
                    "name"=> $request->name,
                    "email"=> $request->email,	
					"username"=> $request->username,						
					"kelas_id"=> $request->kelas,                 
					"password"=> Hash::make($request->password),						
					"username_tiktok"=> $request->username_tiktok,														
                ]);

                if($insert) {
                    DB::commit();
                    return response()->json(['status'=>'insert_successful']);
                } else {
                    return response()->json(['status'=>'insert_failed']);
                }
            } catch (\Throwable $e) {

                DB::rollback();            
                throw $e;            
                return response()->json(['status'=>'insert_failed']);

            }
        } else {
            return redirect('asset/');
        }

    }

    public function update(Request $request, $id)
    {
        if($request->ajax()){

            $update = User::where('user_id', '=', $id)->update([
				"name"=> $request->name,
				"email"=> $request->email,	
				"username"=> $request->username,						
				"kelas_id"=> $request->kelas,                 
				"password"=> Hash::make($request->password),						
				"username_tiktok"=> $request->username_tiktok,		
            ]);

            if($update) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }
	
    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            $query = User::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }
	
	public function NonAktifUser(Request $request)
    {           
        if($request->ajax()){
       
            $update = User::where('user_id', '=', $request->id)->update([              
                "user_status"=> 'n',                
            ]);
            
            if($update) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }
	
	public function AktifUser(Request $request)
    {           
        if($request->ajax()){
       
            $update = User::where('user_id', '=', $request->id)->update([              
                "user_status"=> 'y',                                                                       
            ]);
            
            if($update) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }
	
	public function viewAkses(Request $request, $id)
    {
		$data = array(
            'head' => 'MASTER',
            'title' => 'DETAIL HAK AKSES',
			'judul' => 'AKSES USER',
            'subtitle' => Session::get('subtitle'),
            'alamatKampus' => Session::get('alamat'),
            'btnClass' => 'btn btn-primary btn-sm',
			'btnClassSuccess' => 'btn btn-success btn-sm',
			'btnClassInfo' => 'btn btn-info btn-sm',
            'btnClassDisposisi' => 'btn btn-primary btn-detail',
            'btnAdd' => 'Tambah',
			'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
			'classFormSelect2' => 'single-select',
			'id' => $id,
        );

		return view('man_user/view_hak_akses',compact('data'));

	}
	
	public function getDataAkses(Request $request, $id)
    {
        $dataFile = DB::select(
        
			DB::raw('
				select b.*, a.*, c.submenu_nama as menu_nama 
				from m_submenu as b 
				left join m_submenu_det as a on b.submenu_id = a.id_sub_menu and a.id_user = '.base64_decode($id).'
				left join m_submenu as c on b.submenu_parent = c.submenu_id 
				where b.submenu_status = "y" 
				and b.submenu_link is not null 
				order by b.submenu_child asc, b.submenu_parent asc
			')
        );

        if($dataFile) {
            return response()->json([
                'status'=>'oke',
                'data' => $dataFile
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
    }
}
