<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier\Supplier;
use App\Models\Pemesanan\Pemesanan;
use App\Models\Pemesanan\PemesananDetail;
use App\Models\Penawaran\PO;
use Session;
use Auth;
use Carbon\Carbon;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		        

        $data = array(
            'title' => 'Upload Dokumen Pendukung',
            'subtitle' => Session::get('subtitle'),
			'pass' => Session::get('passAdmin'),
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            'kode' => $kode
        );         
                        
		$returnHTML = view('penerimaan/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    	
	public function list(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Penerimaan Barang',
            'subtitle' => Session::get('subtitle'),
			'pass' => Session::get('passAdmin'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idJb' => $id,
			'status' => "new"
        );   				

        $returnHTML = view('penerimaan/list',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function getData()
    {
        
        $data = DB::table('t_pemesanan as a')
				->leftJoin('m_supplier as b', 'a.id_supplier', '=', 'b.supplier_id')                                    
				->where('a.id_kelas', '=', Session::get('kelas'))			
				->get();

        if($data) {
            return response()->json([
                'status'=>'oke',
                'data' => $data
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	public function getDataTerima()
    {
        
        $data = PO::where('file_tipe','=','T')->get();
        if($data) {
            return response()->json([
                'status'=>'oke',
                'data' => $data
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	public function storeTerima(Request $request)
    {
        if($request->ajax()){            
				
            DB::beginTransaction();
            try {
				if($request->file('file')){				
					
					// membuat nama file unik
					$ext = $request->file('file')->getClientOriginalExtension();
					$size = $request->file('file')->getSize();
					$nama_file = "T_".date("YmdHis").'.pdf';
					$nama_file_ori = $request->file('file')->getClientOriginalName();			
					$path = "siantok/terima/";
					
					$request->file('file')-> move($path, $nama_file);
					$insert = PO::create([						
						"id_pesan"=> $request->id,				
						"dt_record"=> date("Y-m-d H:i:s"),
						"file_size"=> $size,						
						"user_record"=> Session::get('login_as'),
						"file_name"=> $nama_file,
						"file_name_ori"=> $nama_file_ori,
						"file_path"=> $path,
						"file_exe"=> $ext,
						"file_tipe"=> "T",
						"id_kelas"=> Session::get('kelas')
					]);

					if($insert) {
						DB::commit();             
						return response()->json(['status'=>'insert_successful','msg'=>'Data Berhasil Ditambahkan']);                    
					} else {
						return response()->json(['status'=>'insert_failed','msg'=>'Data Gagal Ditambahkan']);
					}
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
      	
	public function destroyDok(Request $request, $id)
    {
        
		$message = "delete_successful";

        if($request->ajax()){
			$query = PO::find($id)->delete();
			
            if($query) {
                return response()->json(['status'=>$message]);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }                    

        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }
    
}
