<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EditPerkiraan;
use Auth;
use Session;
use DB;

class EditPerkiraanController extends Controller
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
            //'title' => 'Edit Kode dan Nama Perkiraan Akuntansi',
			'title' => 'Daftar Nama Perkiraan Akuntansi',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
        );       
				
       $returnHTML = view('edit_perkiraan/index',compact('data'))->render();
       return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
        $EditPerkiraan = EditPerkiraan::where('id_lembaga','=',Session::get('idLembaga'))
						->orderBy('kode_perkiraan', 'ASC')->get();

        if($EditPerkiraan) {
            return response()->json([
                'status'=>'oke',
                'data' => $EditPerkiraan
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
    
    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
            "kode_perkiraan" => "required|unique:m_perkiraan,kode_perkiraan".($id ? ",".$id.",id" : "" ),
            "nama_perkiraan" => "required",			
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
                $insertPerkiraan = EditPerkiraan::create([
                    "kode_perkiraan"=> $request->kode_perkiraan,
                    "nama_perkiraan"=> $request->nama_perkiraan,								
					"id_lembaga"=> Session::get('idLembaga'),
                    "user_record"=> $request->nama_perkiraan,
                    "df_trans_perkiraan"=> $request->keterangan,
                    // "user_record"=> Auth::user()->name,
                    "dt_record"=> date("Y-m-d H:i:s")
                ]);

                if($insertPerkiraan) {
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

            $updateEditPerkiraan = EditPerkiraan::where('id', '=', $id)->update([
                "kode_perkiraan"=> $request->kode_perkiraan,
                "nama_perkiraan"=> $request->nama_perkiraan,	                
                "user_modified"=> $request->nama_perkiraan,
				"id_lembaga"=> Session::get('idLembaga'),
                "df_trans_perkiraan"=> $request->keterangan,
                //"user_modified"=> Auth::user()->name,
                "dt_modified"=> date("Y-m-d H:i:s")
            ]);

            if($updateEditPerkiraan) {
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
            $query = EditPerkiraan::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }

}
