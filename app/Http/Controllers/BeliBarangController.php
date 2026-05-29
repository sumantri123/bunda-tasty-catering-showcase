<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BeliBarang\BeliBarang;
use Auth;
use Session;
use DB;

class BeliBarangController extends Controller
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
			'title' => 'Master Pembelian Barang ',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
        );       
		
		$barang_keluar = DB::select(
					DB::raw('
						SELECT * from m_perkiraan 
						where id_lembaga = "'.Session::get('idLembaga').'"
						and kode_otomatis in ("11XXXX01","11XXXX02","12XXXX01","12XXXX02","12XXXX03",
						"12XXXX04","12XXXX05")
					')
				);
				
		$returnHTML = view('pembelian_barang/index',compact('data','barang_keluar'))->render();
		return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
		
        $cs = DB::select(
					DB::raw('
						SELECT a.*, b.nama_perkiraan
						FROM m_barang_keluar as a 
						left join m_perkiraan as b on a.id_perkiraan = b.id
						where a.id_lembaga = "'.Session::get('idLembaga').'"
						ORDER BY nama_perkiraan asc, m_barang_keluar_nama asc
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
                $insert = BeliBarang::create([
                    "m_barang_keluar_nama"=> $request->nama,                    
					"id_perkiraan"=> $request->kat_barang_keluar,							
					"id_lembaga"=> Session::get('idLembaga'), 															            
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

            $update = BeliBarang::where('m_barang_keluar_id', '=', $id)->update([
				"m_barang_keluar_nama"=> $request->nama,                    
				"id_perkiraan"=> $request->kat_barang_keluar,
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
            $query = BeliBarang::find($id)->delete();
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
