<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\BukaTransaksi;
use Session;
use Auth;

class BukaTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		                 
		
        //$BukaTransaksi = BukaTransaksi::get();           

        $data = array(
            'title' => 'Buka Transaksi',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classFormSelect3' => 'single-select2',
            'classTable' => 'table table-sm table-bordered table-striped'            
        );         
                
        //return view('buka_transaksi/index', compact('data','BukaTransaksi'));        
        $returnHTML = view('buka_transaksi/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );        
        
    }            
   

    public function getDataBukaTransaksi(Request $request)
    {           
        $BukaTransaksi = BukaTransaksi::where('id_kelas','=', Session::get('kelas'))->orderBy('id', 'desc')->get();
        
        if($BukaTransaksi) {
			return response()->json([
                'status'=>'oke',
                'bukaTransaksi' => $BukaTransaksi,
            ]);   
            
        } else {
            return response()->json([
				'status'=>'null'
				]);       
        }
    }
	
	public function NonAktifBukaTransaksi(Request $request)
    {           
        if($request->ajax()){
       
            $update = BukaTransaksi::where('id', '=', $request->id)->update([              
                "buka_aktif"=> 'n',
                "dt_modified"=> date("Y-m-d H:i:s"),
                "user_modified"=> Auth::user()->name                                                        
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
	
	public function AktifBukaTransaksi(Request $request)
    {           
        if($request->ajax()){
       
            $update = BukaTransaksi::where('id', '=', $request->id)->update([              
                "buka_aktif"=> 'y',
                "dt_modified"=> date("Y-m-d H:i:s"),
                "user_modified"=> Auth::user()->name                                                        
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
	
	public function GenTokenBukaTransaksi(Request $request)
    {           
        if($request->ajax()){
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < 5; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			$startTime = date("Y-m-d H:i:s");

			$cenvertedTime = date('Y-m-d H:i:s',strtotime('+3 hours',strtotime($startTime)));

       
            $update = BukaTransaksi::where('id', '=', $request->id)->update([              
                "token"=> $randomString,
				"batas_token"=>$cenvertedTime,
                "dt_modified"=> date("Y-m-d H:i:s"),
                "user_modified"=> Auth::user()->name                                                        
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

    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'numeric' => 'Inputan harus angka.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
//            "nomor_rekening" => "required|unique:t_rekening_nasabah,nomor_rekening".($id ? ",".$id.",id" : "" ),            
        ], $messages);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
			$startTime = date("Y-m-d H:i:s");
	
			$tanggal_add = date('Y-m-d H:i:s',strtotime('+3 hours',strtotime($startTime)));		
            DB::beginTransaction();

			try {
		
				$cekData = DB::table('t_buka_transaksi')                          
					->select('*') 
					->where('buka_tanggal','=',date('Y-m-d', strtotime($request->tgl_buka_transaksi))) 
					->where('id_kelas', '=', Session::get('kelas'))                                
					->count();    


				if($cekData >0){

					return response()->json(['status'=>'insert_failed','msg'=>' Tanggal Sudah Ditambahkan, Silahkan Klik Generate Token Ditanggal Tersebut']);

				} else {

					$insert = BukaTransaksi::create([
						"id_kelas"=> Session::get('kelas'),                        
						"buka_tanggal"=> date('Y-m-d', strtotime($request->tgl_buka_transaksi)),
						"buka_aktif"=> 'y',
						"token"=> $request->token,
						"batas_token"=> $tanggal_add,
						"dt_record"=> date("Y-m-d H:i:s"),
						"user_record"=> Auth::user()->name                 
					]);

					if($insert) {
						DB::commit();
						return response()->json(['status'=>'insert_successful']);
					} else {
						return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);                
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

    // public function update(Request $request, $id)
    // {
    //     if($request->ajax()){
    //         if ($this->validateRequest($request, $id)->fails()) {

    //             // return response()->json([
    //             //     'status'=>'insert_failed',
    //             //     'error' => $this->validateRequest($request, $id)->messages()
    //             //     ]);
    //         }            
            
    //         $nominal = $request->nominal;
    //         $find = [","];
    //         $replace = [""];
    //         $newNominal = str_replace($find, $replace, $nominal);

    //         $update = TestKey::where('test_key_id', '=', $id)->update([              
    //             "test_key_no"=> $request->no_bukti,                
    //             "hasil_test_key"=> "Tested",                    
    //             "nominal"=> $newNominal,                                                            
    //             "id_kelas"=> Session::get('kelas'),                        
    //             "dt_modified"=> date("Y-m-d H:i:s"),
    //             "user_modified"=> Auth::user()->name                                                        
    //         ]);
            
    //         if($update) {
    //             return response()->json(['status'=>'insert_successful','id'=>$id]);                
    //         } else {
    //             return response()->json(['status'=>'insert_failed']);
    //         }
    //     } else {
    //         return response()->json(['status'=>'proses_failed']);
    //     }

    // }

    // public function destroy(Request $request, $id)
    // {
    //     if($request->ajax()){
    //         $query = TestKey::find($id)->delete();
    //         if($query) {
    //             return response()->json(['status'=>'delete_successful']);
    //         } else {
    //             return response()->json(['status'=>'delete_failed']);
    //         }
    //     } else {
    //         return response()->json(['status'=>'delete_failed']);
    //     }
    // }
        

    // public function search(Request $request)
    // {   
    //     $bagian = base64_decode($request->bagian);
    //     $search = $request->kode;

    //     $searchData = TestKey::where('test_key_no','like','%' .$search . '%')->get();          
    //     $pecah = explode("-",$searchData[0]->test_key);
    //     $testKeyKe = $pecah[0];
    //     $testKeyNilai = $pecah[1];

    //     if(count($searchData)>0) {
    //         return response()->json([
    //             'status'=>'oke',                
    //             'jbId'=> $searchData[0]->test_key_id,
    //             'jbNo'=> $searchData[0]->test_key_no,
    //             'jbTgl'=> $searchData[0]->test_key_tgl,            
    //             'jbCbngPengirim'=> $searchData[0]->id_cabang_pengirim,
    //             'jbCbngPenerima'=> $searchData[0]->id_cabang_penerima,
    //             'jbNominal'=> $searchData[0]->nominal,
    //             'jbIdTransfer'=> $searchData[0]->jenis_transfer,
    //             'jbTestKeyKe'=> $testKeyKe,
    //             'jbTestKeyNilai'=> $testKeyNilai,
    //             'jbHasil'=> $searchData[0]->hasil_test_key,
    //             ]);
    //     } else {
    //         return response()->json(['status'=>'failed']);
    //     }                
    // }
}
