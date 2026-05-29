<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pejabat\Pejabat;
use Auth;
use Session;
use DB;

class PejabatController extends Controller
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
			'title' => 'Pejabat',
            'subtitle' => Session::get('subtitle'),		
			'pass' => Session::get('passAdmin'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
        );       
				
       $returnHTML = view('pejabat/index',compact('data'))->render();
       return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
        $cs = DB::select(
					DB::raw('
						SELECT a.*, b.id_pejabat as pejabat_invoice, c.id_pejabat as pejabat_penawaran
						FROM m_pejabat as a 						
						LEFT JOIN 
							(
								select id_pejabat
								from t_penawaran                            								
								group by id_pejabat
							) c on a.pejabat_id = c.id_pejabat
						LEFT JOIN 
							(
								select id_pejabat
								from t_invoice                            								
								group by id_pejabat
							) b on a.pejabat_id = b.id_pejabat
						where a.id_kelas = "'.Session::get('kelas').'"                    
						ORDER BY pejabat_nama asc
					')
				);

        if($cs) {
            return response()->json([
                'status'=>'oke',
                'data' => $cs
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
				if($request->file('file')){		
				
					$ext = $request->file('file')->getClientOriginalExtension();
					$size = $request->file('file')->getSize();
					$nama_file = "img_".date("YmdHis").'.png';
					$nama_file_ori = $request->file('file')->getClientOriginalName();			
					$path = "siantok/images/";
					
					$request->file('file')-> move($path, $nama_file);
					
					$insert = Pejabat::create([
						"pejabat_nama"=> $request->nama,
						"pejabat_alamat"=> $request->alamat,
						"pejabat_path"=> $path,
						"pejabat_name_ori"=> $nama_file_ori,
						"pejabat_name"=> $nama_file,
						"pejabat_exe"=> $ext,
						"pejabat_size"=> $size,
						"id_kelas"=> Session::get('kelas'),
						"user_record"=> Session::get('login_as'),
						"pejabat_jabatan"=> $request->owner,
						"pejabat_telp"=> $request->telp,
						// "user_record"=> Auth::user()->name,
						"dt_record"=> date("Y-m-d H:i:s")
					]);

					if($insert) {
						DB::commit();             
						return response()->json(['status'=>'insert_successful','msg'=>'Data Berhasil Ditambahkan']);                    
					} else {
						return response()->json(['status'=>'insert_failed','msg'=>'Data Gagal Ditambahkan']);
					}
					
				} else {				
				
					$insert = Pejabat::create([
						"pejabat_nama"=> $request->nama,
						"pejabat_alamat"=> $request->alamat,								
						"id_kelas"=> Session::get('kelas'),
						"user_record"=> Session::get('login_as'),
						"pejabat_jabatan"=> $request->owner,
						"pejabat_telp"=> $request->telp,
						// "user_record"=> Auth::user()->name,
						"dt_record"=> date("Y-m-d H:i:s")
					]);

					if($insert) {
						DB::commit();
						return response()->json(['status'=>'insert_successful']);
					} else {
						return response()->json(['status'=>'insert_failed']);
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

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            
			DB::beginTransaction();
			
			try {
				
				if($request->file('file')){		
					
					$ext = $request->file('file')->getClientOriginalExtension();
					$size = $request->file('file')->getSize();
					$nama_file = "img_".date("YmdHis").'.png';
					$nama_file_ori = $request->file('file')->getClientOriginalName();			
					$path = "siantok/images/";
					
					$request->file('file')-> move($path, $nama_file);
					
					$update = Pejabat::where('pejabat_id', '=', $id)->update([
						"pejabat_nama"=> $request->nama,
						"pejabat_alamat"=> $request->alamat,
						"pejabat_path"=> $path,
						"pejabat_name_ori"=> $nama_file_ori,
						"pejabat_name"=> $nama_file,
						"pejabat_exe"=> $ext,
						"pejabat_size"=> $size,
						"id_kelas"=> Session::get('kelas'),
						"user_record"=> Session::get('login_as'),
						"pejabat_jabatan"=> $request->owner,
						"pejabat_telp"=> $request->telp,
						// "user_record"=> Auth::user()->name,
						"dt_record"=> date("Y-m-d H:i:s")
					]);

					if($update) {
						DB::commit();             
						return response()->json(['status'=>'insert_successful','msg'=>'Data Berhasil Ditambahkan']);                    
					} else {
						return response()->json(['status'=>'insert_failed','msg'=>'Data Gagal Ditambahkan']);
					}
					
				} else {	
					
					 $update = Pejabat::where('pejabat_id', '=', $id)->update([
						"pejabat_nama"=> $request->nama,
						"pejabat_alamat"=> $request->alamat,								
						"id_kelas"=> Session::get('kelas'),
						"user_modified"=> Session::get('login_as'),
						"pejabat_jabatan"=> $request->owner,
						"pejabat_telp"=> $request->telp,
						// "user_record"=> Auth::user()->name,
						"dt_modified"=> date("Y-m-d H:i:s")
					]);

					if($update) {
						DB::commit(); 
						return response()->json(['status'=>'insert_successful']);
					} else {
						return response()->json(['status'=>'insert_failed']);
					}
					
				}
			} catch (\Throwable $e) {

                DB::rollback();            
                throw $e;            
                return response()->json(['status'=>'insert_failed']);

            }
           
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }

    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            $query = Pejabat::find($id)->delete();
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
