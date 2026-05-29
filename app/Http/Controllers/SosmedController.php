<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sosmed\Sosmed;
use App\Models\Sosmed\Sosmed;
use Auth;
use Session;
use DB;

class SosmedController extends Controller
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
			'title' => 'Sosial Media',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
        );       
				
       $returnHTML = view('sosmed/index',compact('data'))->render();
       return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
		
        $cs = DB::select(
					DB::raw('
						SELECT *
						FROM m_sosmed as a 												
						where a.id_kelas = "'.Session::get('kelas').'"
						ORDER BY sosmed_jenis asc
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
                $insert = Sosmed::create([
                    "sosmed_jenis"=> $request->jenis,
                    "sosmed_akun"=> $request->akun,	
					"sosmed_link"=> $request->link,						
					"id_kelas"=> Session::get('kelas'),                                       
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

			$update = Sosmed::find($id);
			$update->update([
				"sosmed_jenis"=> $request->jenis,
                "sosmed_akun"=> $request->akun,							
				"sosmed_link"=> $request->link,	
			]);
			$update->save();

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
            $query = Sosmed::find($id)->delete();
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
