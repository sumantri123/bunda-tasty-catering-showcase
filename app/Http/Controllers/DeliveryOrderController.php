<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Penawaran\Penawaran;
use App\Models\Delivery\DeliveryOrder;
use App\Models\Delivery\DeliveryOrderDetail;
use Session;
use Auth;
use Carbon\Carbon;

class DeliveryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		        

        $data = array(
            'title' => 'Delivery Order (DO)',
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
                        
		$returnHTML = view('delivery_order/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    	
	public function list(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Delivery Order (DO)',
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

        $returnHTML = view('delivery_order/list',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }
	
	public function getData()
    {
        
        $penawaran = Penawaran::orderBy('penawaran_tgl', 'DESC')->get();
        if($penawaran) {
            return response()->json([
                'status'=>'oke',
                'data' => $penawaran
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	public function getDataDO()
    {
        
        $do = DeliveryOrder::get();
        if($do) {
            return response()->json([
                'status'=>'oke',
                'data' => $do
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	public function cetak(Request $request, $id)
    {
        $data = DB::select(
                DB::raw('
                    SELECT *
                    FROM t_delivery_order as a 
                    LEFT JOIN t_delivery_order_det as b on a.do_id = b.id_do
                    LEFT JOIN 
                        (
                            select count(id_do) as jumlah, id_do
                            from t_delivery_order_det                            
                            where id_do = '.$id.'                            
							group by id_do
                        ) c on c.id_do = a.do_id
                    where do_id = '.$id.'                                                    
                    and id_kelas = "'.Session::get('kelas').'"                    
                    ORDER BY do_det_id asc
                ')
            );

        return view('delivery_order.cetak', compact('data'));
    }
	
	public function add(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Delivery Order (DO)',
            'subtitle' => Session::get('subtitle'),			
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

        $returnHTML = view('delivery_order/add',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function edit(Request $request, $kode, $id, $idPenawaran)
    {		
		
        $data = array(
            'title' => 'Delivery Order (DO)',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idJb' => $idPenawaran,
			'idDO' => $id,
			'status' => "edit"
        );   				

        $returnHTML = view('delivery_order/add',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }
	
    public function store(Request $request)
    {
        if($request->ajax()){
                
			DB::beginTransaction();

			try {
				
				$year = date('Y', strtotime($request->tgl));
				$month = date('n', strtotime($request->tgl));
				$kode = base64_decode($request->bagian);
				$array_bln	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
				$kodeBulan = $array_bln[$month];
				
				$cekNomor = DeliveryOrder::where('id_kelas','=',Session::get('kelas'))
							->where('do_tahun','=',$year)
							->orderby('do_id','desc')							
							->limit(1)
							->get();
				
				if(count($cekNomor) > 0){
										
					$pecahNomor = explode("/",$cekNomor[0]->do_nomor);
					$lastUrut = $pecahNomor[0];
					$nextUrut = str_pad(($lastUrut+1),3,'0',STR_PAD_LEFT);
					$jenisDok = $pecahNomor[1];
					$kode = $pecahNomor[2];
					$bulan = $pecahNomor[3];
					$tahun = $pecahNomor[4];
					
					$nomer = $nextUrut.'/'.$jenisDok.'/'.$kode.'/'.$bulan.'/'.$tahun;					
					
				} else {
					
					$nomer = "001/DO/".$kode."/".$kodeBulan."/".$year;
				}

				$insert = DeliveryOrder::create([
					"do_nomor"=> $nomer,
					"id_penawaran"=> $request->id_jb,
					"do_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"do_jenis"=> $request->jenis,                    
					"do_header"=> $request->do_header,                    					
					"do_tahun"=> date('Y', strtotime($request->tgl)),
					"do_pejabat"=> $request->pejabat,
					"do_po_nomor"=> $request->no_po,
					"do_ttd"=> $request->ttd,   
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);

				if($insert) {
					DB::commit();
					return response()->json(['status'=>'insert_successful','id'=>$insert->do_id, 'no'=>$nomer]);                
				} else {
					return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);                
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

			try {
								
				$update = DeliveryOrder::where('do_id', '=', $id)->update([                              
					"do_nomor"=> $request->no_bukti,
					"id_penawaran"=> $request->id_jb,
					"do_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"do_jenis"=> $request->jenis,                    
					"do_header"=> $request->do_header,                    					
					"do_tahun"=> date('Y', strtotime($request->tgl)),
					"do_pejabat"=> $request->pejabat,
					"do_po_nomor"=> $request->no_po,   
					"do_ttd"=> $request->ttd,   
					"id_kelas"=> Session::get('kelas'),
					"dt_modified"=> date("Y-m-d H:i:s"),
					"user_modified"=> Session::get('login_as')
				]);
				
				if($update) {
					return response()->json(['status'=>'insert_successful','id'=>$id, 'no'=>$request->no_bukti]);                
				} else {
					return response()->json(['status'=>'insert_failed']);
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
            $query = DeliveryOrder::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }
    

    // untuk jurnal bagian detail
    public function storeDet(Request $request)
    {
        if($request->ajax()){
            
			try {
				
                $insert = DeliveryOrderDetail::create([                    
                    "id_do"=> $request->idDO,
                    "do_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"do_keterangan"=> $request->keterangan,
					"do_satuan"=> $request->satuan,										
                    "dt_record"=> date("Y-m-d H:i:s"),
                    "user_record"=> Session::get('login_as'),   
                ]);
    
                if($insert) {
                    return response()->json(['status'=>'insert_successful','id'=>$insert->do_det_id]);                
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
	
	// untuk jurnal bagian detail
    public function updateDet(Request $request, $id)
    {
        if($request->ajax()){     
		
			try {
				
                
                $update = DeliveryOrderDetail::where('do_det_id', '=', $id)->update([                                      
                    "do_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"do_keterangan"=> $request->keterangan,
					"do_satuan"=> $request->satuan,										
                    "dt_record"=> date("Y-m-d H:i:s"),
                    "user_record"=> Session::get('login_as'),     
                ]);
    
                if($update) {
                    return response()->json(['status'=>'insert_successful','id'=>$id]);                
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

    public function destroyDet(Request $request, $id, $idJB)
    {
        /* $countData = PenawaranDetail::where('id_penawaran','=',$idJB)->get()->count();
        $message = ($countData>1) ? "delete_successful":"delete_successfulx"; */
		$message = "delete_successful";

        if($request->ajax()){
			$query = DeliveryOrderDetail::find($id)->delete();
			
            /* if($countData>1){
                $query = PenawaranDetail::find($id)->delete();
            } else {
                $query = Penawaran::find($idJB)->delete();
            } */
			
            if($query) {
                return response()->json(['status'=>$message]);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }                    

        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }		    

    public function search(Request $request)
    {   
        
        $bagian = base64_decode($request->kode);
        $idDO = $request->idDO;

		$searchData = DB::select(
                DB::raw('
                    SELECT *
                    FROM t_delivery_order as a 
                    LEFT JOIN t_delivery_order_det as b on a.do_id = b.id_do
                    LEFT JOIN 
                        (
                            select count(id_do) as jumlah, id_do
                            from t_delivery_order_det                            
                            where id_do = '.$idDO.'                            
							group by id_do
                        ) c on c.id_do = a.do_id
                    where do_id = '.$idDO.'                                                    
                    and id_kelas = "'.Session::get('kelas').'"                    
                    ORDER BY do_det_id asc
                ')
            );

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'doId'=> $searchData[0]->do_id,
				'idPenawaran'=> $searchData[0]->id_penawaran,
                'doNo'=> $searchData[0]->do_nomor,
                'doJenis'=> $searchData[0]->do_jenis,
                'doTgl'=> $searchData[0]->do_tgl,                
				'doHeader'=> $searchData[0]->do_header,				
				'doTtd'=> $searchData[0]->do_ttd,
				'doPejabat'=> $searchData[0]->do_pejabat,				
				'jumlahData'=> $searchData[0]->jumlah,
                'data' => $searchData
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }
}
