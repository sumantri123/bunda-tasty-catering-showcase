<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier\Supplier;
use Auth;
use Session;
use DB;

class SupplierController extends Controller
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
			'title' => 'Supplier',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
        );       
				
       $returnHTML = view('supplier/index',compact('data'))->render();
       return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
        
		$supplier = DB::select(
					DB::raw('
						SELECT *
						FROM m_supplier as a 						
						LEFT JOIN 
							(
								select id_supplier
								from t_pemesanan                            								
								group by id_supplier
							) c on a.supplier_id = c.id_supplier
						where a.id_kelas = "'.Session::get('kelas').'"                    
						ORDER BY supplier_nama asc
					')
				);
        if($supplier) {
            return response()->json([
                'status'=>'oke',
                'data' => $supplier
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
            "nama" => "required",            
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
                $insert = Supplier::create([
                    "supplier_nama"=> $request->nama,
                    "supplier_alamat"=> $request->alamat,								
					"id_kelas"=> Session::get('kelas'),
                    "user_record"=> Session::get('login_as'),
                    "supplier_pejabat"=> $request->owner,
					"supplier_telp"=> $request->telp,
                    // "user_record"=> Auth::user()->name,
                    "dt_record"=> date("Y-m-d H:i:s")
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

            $update = Supplier::where('supplier_id', '=', $id)->update([
				"supplier_nama"=> $request->nama,
				"supplier_alamat"=> $request->alamat,								
				"id_kelas"=> Session::get('kelas'),
				"user_modified"=> Session::get('login_as'),
				"supplier_pejabat"=> $request->owner,
				"supplier_telp"=> $request->telp,
				// "user_record"=> Auth::user()->name,
				"dt_modified"=> date("Y-m-d H:i:s")
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
            $query = Supplier::find($id)->delete();
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
