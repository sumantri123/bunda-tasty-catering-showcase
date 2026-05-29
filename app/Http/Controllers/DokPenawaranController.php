<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Customer\Customer;
use App\Models\Pejabat\Pejabat;
use App\Models\Menu\Menu;
use App\Models\Penawaran\PO;
use App\Models\Penawaran\Penawaran;
use App\Models\Penawaran\PenawaranDetail;
use App\Models\EditPerkiraan;
use Session;
use Auth;
use Carbon\Carbon;

class DokPenawaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		        

        $data = array(
            'title' => 'Dokumen Penawaran (Quotation)',
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
                        
		$returnHTML = view('dok_penawaran/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    
	public function getData()
    {
        
        $penawaran = Penawaran::orderBy('penawaran_tgl', 'DESC')
					->where('id_kelas','=',Session::get('kelas'))
					->get();
        if($penawaran) {
            return response()->json([
                'status'=>'oke',
                'data' => $penawaran
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	public function getDataPO()
    {
        
        $po = PO::where('id_jenis_file','=',1)->get();
        if($po) {
            return response()->json([
                'status'=>'oke',
                'data' => $po
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
						FROM t_penawaran as a 
						LEFT JOIN t_penawaran_detail as b on a.penawaran_id = b.id_penawaran
						left join m_pejabat as c on a.id_pejabat = c.pejabat_id
						LEFT JOIN 
							(
								select count(id_penawaran) as jumlah, sum(harga) as total_harga,
								sum(pajak_nominal) as total_pajak, sum(total) as grand_total,
								id_penawaran
								from t_penawaran_detail                            
								where id_penawaran = '.$id.'                            
								group by id_penawaran
							) c on c.id_penawaran = a.penawaran_id
						where penawaran_id = '.$id.'                                                    
						and a.id_kelas = "'.Session::get('kelas').'"                    
						ORDER BY penawaran_detail_id asc
					')
				);

        return view('dok_penawaran.cetak', compact('data'));
    }
	
	public function add($kode)
    {		
		
        $data = array(
            'title' => 'Dokumen Penawaran (Quotation)',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idJb' => '',
			'status' => "new"
        );   				
		$customer = Customer::get();
		$pejabat = Pejabat::get();
        $returnHTML = view('dok_penawaran/add',compact('data','customer','pejabat'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function edit(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Dokumen Penawaran (Quotation)',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idJb' => $id,
			'status' => "edit"
        );   				
		$customer = Customer::get();
		$pejabat = Pejabat::get();
        $returnHTML = view('dok_penawaran/add',compact('data','customer','pejabat'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function upload(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Upload PO',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idJb' => $id,
			
        );   				

        $returnHTML = view('dok_penawaran/upload',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }	
	
	public function storePO(Request $request)
    {
        if($request->ajax()){            
				
            DB::beginTransaction();
            try {
				if($request->file('file')){				
					
					// membuat nama file unik
					$ext = $request->file('file')->getClientOriginalExtension();
					$size = $request->file('file')->getSize();
					$nama_file = "PO_".date("ymdHis").'.pdf';
					$nama_file_ori = $request->file('file')->getClientOriginalName();			
					$path = "siantok/po/";
					
					$request->file('file')-> move($path, $nama_file);
					$insert = PO::create([						
						"id_penawaran"=> $request->id,				
						"dt_record"=> date("Y-m-d H:i:s"),
						"file_size"=> $size,						
						"user_record"=> Session::get('login_as'),
						"file_name"=> $nama_file,
						"file_name_ori"=> $nama_file_ori,
						"file_path"=> $path,
						"file_exe"=> $ext,
						"id_jenis_file"=> 1,
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
				
				$cekNomor = Penawaran::where('id_kelas','=',Session::get('kelas'))
							->where('penawaran_tahun','=',$year)
							->orderby('penawaran_id','desc')														
							->get();

				
				if(count($cekNomor) > 0){
					
					$pecahNomor = explode("/",$cekNomor[0]->penawaran_nomor);
					$lastUrut = $pecahNomor[0];
					$nextUrut = str_pad(($lastUrut+1),3,'0',STR_PAD_LEFT);
					$jenisDok = $pecahNomor[1];
					$kode = $pecahNomor[2];
					$bulan = $pecahNomor[3];
					$tahun = $pecahNomor[4];
					
					$nomer = $nextUrut.'/'.$jenisDok.'/'.$kode.'/'.$bulan.'/'.$tahun;					
					
				} else {
					
					$nomer = "001/Q/".$kode."/".$kodeBulan."/".$year;
				}
				
				//$pajakGlobal = $request->pajak_global;
				$pajakGlobal = 0;
				
				$insert = Penawaran::create([
					"penawaran_nomor"=> $nomer,
					"penawaran_hal"=> $request->perihal,
					"id_customer"=> $request->company_id,
					"id_pejabat"=> $request->pejabat_id,
					"penawaran_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"penawaran_company"=> $request->company,                    
					"penawaran_header"=> $request->penawaran_header,                    
					"penawaran_content"=> $request->penawaran_content,
					"penawaran_ttd"=> $request->ttd,
					"penawaran_pejabat"=> $request->pejabat,
					"penawaran_hp"=> $request->telp,
					"penawaran_pajak"=> $pajakGlobal,
					"penawaran_tahun"=> date('Y', strtotime($request->tgl)),
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);

				if($insert) {
					DB::commit();
					return response()->json(['status'=>'insert_successful','id'=>$insert->penawaran_id, 'no'=>$nomer]);                
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
				
				$data = PenawaranDetail::where('id_penawaran','=',$id)->get();
							
				$update = Penawaran::where('penawaran_id', '=', $id)->update([                              
					"penawaran_nomor"=> $request->no_bukti,
					"penawaran_hal"=> $request->perihal,
					"id_customer"=> $request->company_id,
					"id_pejabat"=> $request->pejabat_id,
					"penawaran_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"penawaran_company"=> $request->company,                    
					"penawaran_header"=> $request->penawaran_header,                    
					"penawaran_content"=> $request->penawaran_content,
					"penawaran_ttd"=> $request->ttd,
					"penawaran_pejabat"=> $request->pejabat,
					"penawaran_hp"=> $request->telp,
					"penawaran_tahun"=> date('Y', strtotime($request->tgl)),
					"penawaran_pajak"=> $request->pajak_global,
					"id_kelas"=> Session::get('kelas'),
					"dt_modified"=> date("Y-m-d H:i:s"),
					"user_modified"=> Session::get('login_as')
				]);
				
				for($a=0; $a<count($data); $a++){
					
					$update2 = PenawaranDetail::where('penawaran_detail_id', '=', $data[$a]->penawaran_detail_id)->update([                              
						"pajak_persen"=> $request->pajak_global,
						"pajak_nominal"=> (($request->pajak_global * $data[$a]->total) /100),
					]);
				}
				
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
            $query = Penawaran::find($id)->delete();
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
				
                $nominal = $request->total;

                $find = [",00","."];
                $replace = [""];
                $newNominal = str_replace($find, $replace, $nominal);
				$pajak = $request->pajak;
				
				
                $insert = PenawaranDetail::create([                    
                    "id_penawaran"=> $request->idJB,
					"id_perkiraan"=> $request->id_perkiraan,
                    "penawaran_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"harga"=> $request->harga,
					"total"=> $newNominal,					
					"pajak_persen"=> $pajak,					
					"pajak_nominal"=> ($newNominal * $pajak)/100,					
                    "dt_record"=> date("Y-m-d H:i:s"),
                    "user_record"=> Session::get('login_as'),   
					"id_menu"=> $request->menu_id,
                ]);
    
                if($insert) {
                    return response()->json(['status'=>'insert_successful','id'=>$insert->penawaran_detail_id]);                
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
				
                $nominal = $request->total;
                $find = [",00","."];
                $replace = [""];
                $newNominal = str_replace($find, $replace, $nominal);
				$pajak = $request->pajak;
	
                $update = PenawaranDetail::where('penawaran_detail_id', '=', $id)->update([                  
                    "id_penawaran"=> $request->idJB,
                    "penawaran_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"harga"=> $request->harga,
					"total"=> $newNominal,
					"pajak_persen"=> $pajak,					
					"pajak_nominal"=> ($newNominal * $pajak)/100,						
                    "dt_record"=> date("Y-m-d H:i:s"),
                    "user_record"=> Session::get('login_as'),   
					"id_menu"=> $request->menu_id,
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
			$query = PenawaranDetail::find($id)->delete();
			
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
	
	public function destroyPO(Request $request, $id)
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

    public function totalDet(Request $request, $id)
    {
		$data = DB::select(
                DB::raw('
                    SELECT sum(harga) as total_harga, sum(total) as total, sum(pajak_nominal) as total_pajak, 
					count(id_penawaran) as jumlah_data
                    FROM t_penawaran_detail
                    where id_penawaran = '.$id.'
                ')
            );
			        
        if($data) {
            return response()->json([
                'status'=>'oke',                
                'totDebet' => $data[0]->total_harga,
                'totKredit' => $data[0]->total,
				'totPajak' => $data[0]->total_pajak,
				'jumlahData' => $data[0]->jumlah_data,
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
    }

    public function search(Request $request)
    {   
        
        $bagian = base64_decode($request->kode);
        $idPenawaran = $request->idJb;

		$searchData = DB::select(
                DB::raw('
                    SELECT a.*, b.*, c.*, d.kode_perkiraan
                    FROM t_penawaran as a 
                    LEFT JOIN t_penawaran_detail as b on a.penawaran_id = b.id_penawaran
					LEFT JOIN m_perkiraan as d on b.id_perkiraan = d.id
                    LEFT JOIN 
                        (
                            select count(id_penawaran) as jumlah, id_penawaran
                            from t_penawaran_detail                            
                            where id_penawaran = '.$idPenawaran.'                            
							group by id_penawaran
                        ) c on c.id_penawaran = a.penawaran_id
                    where penawaran_id = '.$idPenawaran.'                                                    
                    and id_kelas = "'.Session::get('kelas').'"                    
                    ORDER BY penawaran_detail_id asc
                ')
            );

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'penawaranId'=> $searchData[0]->penawaran_id,
				'idCustomer'=> $searchData[0]->id_customer,
				'idPejabat'=> $searchData[0]->id_pejabat,
                'penawaranNo'=> $searchData[0]->penawaran_nomor,
                'penawaranHal'=> $searchData[0]->penawaran_hal,
                'penawaranTgl'=> $searchData[0]->penawaran_tgl,
                'penawaranCompany'=> $searchData[0]->penawaran_company,
				'penawaranHeader'=> $searchData[0]->penawaran_header,
				'penawaranContent'=> $searchData[0]->penawaran_content,
				'penawaranTtd'=> $searchData[0]->penawaran_ttd,
				'penawaranPejabat'=> $searchData[0]->penawaran_pejabat,
				'penawaranHp'=> $searchData[0]->penawaran_hp,
				'penawaranPajak'=> $searchData[0]->penawaran_pajak,				
				'jumlahData'=> $searchData[0]->jumlah,
                'data' => $searchData
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }

	public function getIdPerkiraan(Request $request)
    {   
        $search = $request->search;

        if($search == ''){
            $LEditPerkiraan = DB::table('m_menu as a')
						->leftJoin('m_perkiraan as b', 'a.id_perkiraan', '=', 'b.id')            
						->select('id','kode_perkiraan','nama_perkiraan','menu_nama','menu_id','menu_harga')
						->where('a.id_lembaga','=',Session::get('idLembaga'))				
						->limit(10)
						->get();          
        }else{                

			$LEditPerkiraan = DB::table('m_menu as a')
						->leftJoin('m_perkiraan as b', 'a.id_perkiraan', '=', 'b.id')            
						->select('id','kode_perkiraan','nama_perkiraan','menu_nama','menu_id','menu_harga')
						->where('a.id_lembaga','=',Session::get('idLembaga'))	
						->where('menu_nama', 'like', '%'. $search . '%')									
						->limit(10)
						->get();          

        }

        $response = array();
        if($LEditPerkiraan->isEmpty()) {
                $response[] = array("value"=>"0","label"=>"Note.Kode Tidak Ada");
        } else {
            foreach($LEditPerkiraan as $EditPerkiraan){
                $response[] = array("value"=>$EditPerkiraan->id.'-'.$EditPerkiraan->menu_id.'-'.$EditPerkiraan->menu_harga,"label"=>$EditPerkiraan->kode_perkiraan.'-'.$EditPerkiraan->menu_nama);
            }
        }

        return response()->json($response);
    }
}
