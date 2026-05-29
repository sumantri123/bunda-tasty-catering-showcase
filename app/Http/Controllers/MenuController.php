<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu\Menu;
use Auth;
use Session;
use DB;

class MenuController extends Controller
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
			'title' => 'Daftar Menu dan Harga',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
        );       

		$menu = DB::select(
					DB::raw('
						SELECT * from m_perkiraan 
						where id_lembaga = "'.Session::get('idLembaga').'"
						and kode_otomatis in ("41XXXX01","41XXXX02","41XXXX03","41XXXX04","41XXXX05",
						"41XXXX06","41XXXX07","41XXXX08","41XXXX09","41XXXX10","41XXXX11","41XXXX12",
						"41XXXX13","41XXXX14","41XXXX15","41XXXX16","41XXXX17","41XXXX18")
					')
				);

		$returnHTML = view('menu/index',compact('data','menu'))->render();
		return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
		
        $cs = DB::select(
					DB::raw('
						SELECT a.*, b.nama_perkiraan
						FROM m_menu as a 
						left join m_perkiraan as b on a.id_perkiraan = b.id
						where a.id_lembaga = "'.Session::get('idLembaga').'"
						ORDER BY nama_perkiraan asc, menu_nama asc
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
            // if ($this->validateRequest($request)->fails()) {
			// 	return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request)->messages()
            //         ]);

            // }
            DB::beginTransaction();

            try {
                $insert = Menu::create([
                    "menu_nama"=> $request->nama,
                    "menu_harga"=> $request->harga,	
					"id_perkiraan"=> $request->kat_menu,							
					"id_lembaga"=> Session::get('idLembaga'), 										
					"menu_who"=> Session::get('login_as'),					
					"menu_when"=> date("Y-m-d H:i:s")                                   
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
            // if ($this->validateRequest($request, $id)->fails()) {

            //     return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request, $id)->messages()
            //         ]);
            // }

            $update = Menu::where('menu_id', '=', $id)->update([
				"menu_nama"=> $request->nama,
				"menu_harga"=> $request->harga,	
				"id_perkiraan"=> $request->kat_menu,															
				"menu_who"=> Session::get('login_as'),					
				"menu_when"=> date("Y-m-d H:i:s")  	
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
            $query = Menu::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }
	
	public function NonAktifMenu(Request $request)
    {           
        if($request->ajax()){
       
            $update = Menu::where('menu_id', '=', $request->id)->update([              
                "menu_status"=> 'n',                
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
	
	public function AktifMenu(Request $request)
    {           
        if($request->ajax()){
       
            $update = Menu::where('menu_id', '=', $request->id)->update([              
                "menu_status"=> 'y',                                                                       
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
}
