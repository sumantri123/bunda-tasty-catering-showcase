<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Pejabat\Pejabat;
use App\Models\Penawaran\PO;
use App\Models\Penawaran\Penawaran;
use App\Models\Penawaran\PenawaranDetail;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceDetail;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use Session;
use Auth;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		        

        $data = array(
            'title' => 'Invoice',
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
                        
		$returnHTML = view('invoice/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    	
	public function list(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Invoice',
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

        $returnHTML = view('invoice/list',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }
	
	public function getData()
    {
                
		$penawaran = DB::select(
					DB::raw('
						select * from t_penawaran as a						
						left join (
							SELECT count(invoice_id) as total, id_penawaran, tipe_pembayaran
							FROM t_invoice
							group by id_penawaran, tipe_pembayaran
						) as b on a.penawaran_id = b.id_penawaran	
						where id_kelas = '.Session::get('kelas').'
						order by penawaran_id desc
					')
				);
       // if($penawaran) {
            return response()->json([
                'status'=>'oke',
                'data' => $penawaran
                ]);
        /* } else {
            return response()->json(['status'=>'failed']);
        } */

    }
	
	public function getDataList(Request $request, $id)
    {
        
       // $invoice = Invoice::get();
		$invoice = DB::select(
					DB::raw('
						select * from t_invoice as a
						left join (
							SELECT sum(total) as total, sum(pajak_nominal) as pajak, id_invoice						
							FROM t_invoice_det
							group by id_invoice
						) as b on a.invoice_id = b.id_invoice
						where id_penawaran = '.$id.'
					')
				);

        if($invoice) {
            return response()->json([
                'status'=>'oke',
                'data' => $invoice
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	public function getDataFaktur(Request $request, $id)
    {
        
        $po = PO::where('id_jenis_file','=',2)
				->where('id_invoice','=',$id)
				->get();
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
                    FROM t_invoice as a 
                    LEFT JOIN t_invoice_det as b on a.invoice_id = b.id_invoice		
					left join t_penawaran as c on a.id_penawaran = c.penawaran_id			
					left join m_pejabat as d on a.id_pejabat = d.pejabat_id
                    LEFT JOIN 
                        (
                            select count(id_invoice) as jumlah, sum(harga) as total_harga,
							sum(pajak_nominal) as total_pajak, sum(total) as grand_total, id_invoice
                            from t_invoice_det                            
                            where id_invoice = '.$id.'                            
							group by id_invoice
                        ) c on c.id_invoice = a.invoice_id
                    where invoice_id = '.$id.'                                                    
                    and a.id_kelas = "'.Session::get('kelas').'"                    
                    ORDER BY invoice_det_id asc
                ')
            );

        return view('invoice.cetak', compact('data'));
    }

	public function add(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Invoice',
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
		$pejabat = Pejabat::get();
        $returnHTML = view('invoice/add',compact('data','pejabat'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function edit(Request $request, $kode, $id, $idPenawaran)
    {		
		
        $data = array(
            'title' => 'Invoice',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idJb' => $idPenawaran,
			'idInvoice' => $id,
			'status' => "edit"
        );   				
		$pejabat = Pejabat::get();
        $returnHTML = view('invoice/add',compact('data','pejabat'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function upload(Request $request, $kode, $id, $id2)
    {		
		
        $data = array(
            'title' => 'Upload Faktur',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idInvoice' => $id,
			'idPenawaran' => $id2,
			
        );   				

        $returnHTML = view('invoice/upload',compact('data'))->render();
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
					$nama_file = "faktur_".date("YmdHis").'.pdf';
					$nama_file_ori = $request->file('file')->getClientOriginalName();			
					$path = "siantok/faktur/";
					
					$request->file('file')-> move($path, $nama_file);
					$insert = PO::create([						
						"id_invoice"=> $request->id,				
						"id_penawaran"=> $request->id_penawaran,				
						"dt_record"=> date("Y-m-d H:i:s"),
						"file_size"=> $size,						
						"user_record"=> Session::get('login_as'),
						"file_name"=> $nama_file,
						"file_name_ori"=> $nama_file_ori,
						"file_path"=> $path,
						"file_exe"=> $ext,
						"id_jenis_file"=> 2,
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
	
	public function generate(Request $request, $id, $param)
    {
        if($request->ajax()){
                
			DB::beginTransaction();

			try {
				
				$year = date('Y');
				$date = date('Y-m-d');
				
				$cekUrutan = Invoice::where('id_kelas','=',Session::get('kelas'))
							->where('invoce_tgl','=',$date)
							->orderby('invoice_id','desc')							
							->limit(1)
							->get();
							
				if(count($cekUrutan) > 0){
										
					$lastUrut = $cekUrutan[0]->invoice_ke;					
					$ke = str_pad(($lastUrut+1),2,'0',STR_PAD_LEFT);					
					
					$nomer = "I-".$ke.date('dmy');					
					
				} else {
					$ke = "01";
					$nomer = "I-01".date('dmy');
				}				
				
				$penawaran = Penawaran::where('penawaran_id','=',$id)->get();				
				$penawaranDetail = PenawaranDetail::where('id_penawaran','=',$id)->get();				
				$flag = 1; //0: dari menu jurnal bagian, 1: dari transaksi penjualan / pembelian
				$dataParam = explode("-",$param);
				$tipe = $dataParam[0];
				$dp = $dataParam[1];
				
				$insert = Invoice::create([
					"invoice_nomor"=> $nomer,
					"id_penawaran"=> $id,
					"id_pejabat"=> $penawaran[0]->id_pejabat,
					"invoce_tgl"=> $date,
					"invoice_due_date"=> $date,
					"invoice_po_nomor"=> '',                    
					"invoice_pajak_persen"=> $penawaran[0]->penawaran_pajak,                    
					"invoice_tlp"=> $penawaran[0]->penawaran_hp,                    					
					"invoice_ke"=> $ke,                    					
					"invoice_tahun"=> date('Y'),
					"invoice_pejabat"=> $penawaran[0]->penawaran_pejabat,
					"tipe_pembayaran"=> $tipe,
					"invoice_ttd"=> $penawaran[0]->penawaran_ttd,
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);

				$total = 0;
				for($a=0; $a<count($penawaranDetail); $a++){
					
					$total += $penawaranDetail[$a]->total;
					 $insertDetail = InvoiceDetail::create([                    
						"id_invoice"=> $insert->invoice_id,
						"invoice_deskripsi"=> $penawaranDetail[$a]->penawaran_deskripsi,
						"qty"=> $penawaranDetail[$a]->qty,
						"harga"=> $penawaranDetail[$a]->harga,
						"total"=> $penawaranDetail[$a]->total,
						"pajak_nominal"=> $penawaranDetail[$a]->pajak_nominal,
						"pajak_persen"=> $penawaranDetail[$a]->pajak_persen,
						"dt_record"=> date("Y-m-d H:i:s"),
						"user_record"=> Session::get('login_as'),   
					]);
					
				}
				
				// Insert di Jurnal Bagian
				$insertJurnal = JurnalBagian::create([
					"jurnal_no"=> $nomer,
					"jurnal_keterangan"=> $penawaran[0]->penawaran_hal,
					"jurnal_tanggal"=> date("Y-m-d"),
					"jurnal_bagian"=> "PJ",                    
					"kode_transaksi"=> "PJ",                    
					"flag"=> $flag,
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);

				if($tipe == 1){
	
					//Pembayaran Lunas
					$idJenisTransaksi = [1,2];
					
					$insertJB = JurnalBagianDetail::create([
						"id_perkiraan"=> Session::get('11XXXKAS_ID'),
						"id_jurnal_bagian"=> $insertJurnal->jurnal_bagian_id,
						"id_jenis_transaksi"=> $idJenisTransaksi[0],
						"jurnal_det_nominal"=> $total,
						"dt_record"=> date("Y-m-d H:i:s"),
						"user_record"=> Session::get('login_as'),   
					]);
					
					for($a=0; $a<count($penawaranDetail); $a++){
											
						 $insert = JurnalBagianDetail::create([
							"id_perkiraan"=> $penawaranDetail[$a]->id_perkiraan,
							"id_jurnal_bagian"=> $insertJurnal->jurnal_bagian_id,
							"id_jenis_transaksi"=> $idJenisTransaksi[1],
							"jurnal_det_nominal"=> $penawaranDetail[$a]->total,
							"dt_record"=> date("Y-m-d H:i:s"),
							"user_record"=> Session::get('login_as'),   
						]);
						
					}

				} else if($tipe == 3){
					
					//Pembayaran Belum Lunas					
					$idJenisTransaksi = [1,2];
					
					$insertJB = JurnalBagianDetail::create([
						"id_perkiraan"=> Session::get('11PUTANG_ID'),
						"id_jurnal_bagian"=> $insertJurnal->jurnal_bagian_id,
						"id_jenis_transaksi"=> $idJenisTransaksi[0],
						"jurnal_det_nominal"=> $total,
						"dt_record"=> date("Y-m-d H:i:s"),
						"user_record"=> Session::get('login_as'),   
					]);
					
					for($a=0; $a<count($penawaranDetail); $a++){
											
						 $insert = JurnalBagianDetail::create([
							"id_perkiraan"=> $penawaranDetail[$a]->id_perkiraan,
							"id_jurnal_bagian"=> $insertJurnal->jurnal_bagian_id,
							"id_jenis_transaksi"=> $idJenisTransaksi[1],
							"jurnal_det_nominal"=> $penawaranDetail[$a]->total,
							"dt_record"=> date("Y-m-d H:i:s"),
							"user_record"=> Session::get('login_as'),   
						]);
						
					}

				} else {

					//Pembayaran DP 
					//(Konfirmasi ke bu Riski untuk DP pada waktu kredit ke penjualan produck dibebankan kemana ?)					
					$idJenisTransaksi = [1,2];
					
					$insertJB = JurnalBagianDetail::create([
						"id_perkiraan"=> Session::get('13PDMUKA_ID'),
						"id_jurnal_bagian"=> $insertJurnal->jurnal_bagian_id,
						"id_jenis_transaksi"=> $idJenisTransaksi[0],
						"jurnal_det_nominal"=> $total,
						"dt_record"=> date("Y-m-d H:i:s"),
						"user_record"=> Session::get('login_as'),   
					]);
					
					for($a=0; $a<count($penawaranDetail); $a++){
											
						 $insert = JurnalBagianDetail::create([
							"id_perkiraan"=> $penawaranDetail[$a]->id_perkiraan,
							"id_jurnal_bagian"=> $insertJurnal->jurnal_bagian_id,
							"id_jenis_transaksi"=> $idJenisTransaksi[1],
							"jurnal_det_nominal"=> $penawaranDetail[$a]->total,
							"dt_record"=> date("Y-m-d H:i:s"),
							"user_record"=> Session::get('login_as'),   
						]);
						
					}

				}
				
				if(count($penawaranDetail) == 0){
					return response()->json(['status'=>'insert_failed','msg'=>'Detail Penawaran Masih Belum Ada']);                
				}elseif(count($penawaranDetail) > 0) {
					DB::commit();
					return response()->json(['status'=>'insert_successful','id'=>$insert->invoice_id, 'no'=>$nomer]);                
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
	
    public function store(Request $request)
    {
        if($request->ajax()){
                
			DB::beginTransaction();

			try {
				
				$year = date('Y', strtotime($request->tgl));
				$date = date('Y-m-d', strtotime($request->tgl));
				
				$cekUrutan = Invoice::where('id_kelas','=',Session::get('kelas'))
							->where('invoce_tgl','=',$date)
							->orderby('invoice_id','desc')							
							->limit(1)
							->get();
							
				if(count($cekUrutan) > 0){
										
					$lastUrut = $cekUrutan[0]->invoice_ke;					
					$ke = str_pad(($lastUrut+1),2,'0',STR_PAD_LEFT);					
					
					$nomer = $ke.date('dmY', strtotime($request->tgl));					
					
				} else {
					$ke = "01";
					$nomer = "01".date('dmY', strtotime($request->tgl));
				}				
				
				$insert = Invoice::create([
					"invoice_nomor"=> $nomer,
					"id_penawaran"=> $request->id_jb,
					"id_pejabat"=> $request->pejabat_id,
					"invoce_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"invoice_due_date"=> date('Y-m-d', strtotime($request->due_tgl)),
					"invoice_po_nomor"=> $request->no_po,                    
					"invoice_pajak_persen"=> $request->pajak_global,                    
					"invoice_tlp"=> $request->no_telp,                    					
					"invoice_ke"=> $ke,                    					
					"invoice_tahun"=> date('Y', strtotime($request->tgl)),
					"invoice_pejabat"=> $request->pejabat,   
					"invoice_ttd"=> $request->ttd,   
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);

				if($insert) {
					DB::commit();
					return response()->json(['status'=>'insert_successful','id'=>$insert->invoice_id, 'no'=>$nomer]);                
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

	public function storeDisc(Request $request)
    {
		
        if($request->ajax()){
                
			DB::beginTransaction();

			try {
												
				$update = Invoice::where('invoice_id', '=', $request->idInvoice)->update([   
					"invoice_nominal"=> $request->disc,					
				]);

				if($update) {
					DB::commit();
					return response()->json(['status'=>'insert_successful']);                
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
				
				$data = InvoiceDetail::where('id_invoice','=',$id)->get();
			
				$update = Invoice::where('invoice_id', '=', $id)->update([                              
					"invoice_nomor"=> $request->no_bukti,
					"id_penawaran"=> $request->id_jb,
					"id_pejabat"=> $request->pejabat_id,
					"invoce_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"invoice_due_date"=> date('Y-m-d', strtotime($request->due_tgl)),
					"invoice_po_nomor"=> $request->no_po,                    
					"invoice_pajak_persen"=> $request->pajak_global,                    
					"invoice_tlp"=> $request->no_telp,                    					
					"invoice_ke"=> $request->ke,                  					
					"invoice_tahun"=> date('Y', strtotime($request->tgl)),
					"invoice_pejabat"=> $request->pejabat,   
					"invoice_ttd"=> $request->ttd,   
					"id_kelas"=> Session::get('kelas'),
					"dt_modified"=> date("Y-m-d H:i:s"),
					"user_modified"=> Session::get('login_as')
				]);
				
				for($a=0; $a<count($data); $a++){
					
					$update2 = InvoiceDetail::where('invoice_det_id', '=', $data[$a]->invoice_det_id)->update([                              
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
            $query = Invoice::find($id)->delete();
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

                $insert = InvoiceDetail::create([                    
                    "id_invoice"=> $request->idInvoice,
                    "invoice_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"harga"=> $request->harga,
					"total"=> $newNominal,	
					"pajak_nominal"=> ($request->harga * $request->qty * $pajak)/100,
					"pajak_persen"=> $pajak,					
                    "dt_record"=> date("Y-m-d H:i:s"),
                    "user_record"=> Session::get('login_as'),   
                ]);
    
                if($insert) {
                    return response()->json(['status'=>'insert_successful','id'=>$insert->invoice_det_id]);                
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
                
                $update = InvoiceDetail::where('invoice_det_id', '=', $id)->update([                                      
                    "id_invoice"=> $request->idInvoice,
                    "invoice_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"harga"=> $request->harga,
					"total"=> $newNominal,	
					"pajak_nominal"=> ($request->harga * $request->qty * $pajak)/100,
					"pajak_persen"=> $pajak,					
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
	
	public function destroyFaktur(Request $request, $id)
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
						count(id_invoice) as jumlah_data, invoice_nominal
						FROM t_invoice_det as a
						left join t_invoice as b on a.id_invoice = b.invoice_id
						where id_invoice = '.$id.'
					')
				);
			        
        if($data) {
            return response()->json([
                'status'=>'oke',                
                'totDebet' => $data[0]->total_harga,
                'totKredit' => $data[0]->total,
				'totPajak' => $data[0]->total_pajak,
				'totDiscount' => $data[0]->invoice_nominal,
				'jumlahData' => $data[0]->jumlah_data,
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
    }

    public function search(Request $request)
    {   
        
        $bagian = base64_decode($request->kode);
        $idInvoice = $request->idInvoice;

		$searchData = DB::select(
                DB::raw('
                    SELECT *
                    FROM t_invoice as a 
                    LEFT JOIN t_invoice_det as b on a.invoice_id = b.id_invoice
                    LEFT JOIN 
                        (
                            select count(id_invoice) as jumlah, id_invoice
                            from t_invoice_det                            
                            where id_invoice = '.$idInvoice.'                            
							group by id_invoice
                        ) c on c.id_invoice = a.invoice_id
                    where invoice_id = '.$idInvoice.'                                                    
                    and id_kelas = "'.Session::get('kelas').'"                    
                    ORDER BY invoice_det_id asc
                ')
            );

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'invoiceId'=> $searchData[0]->invoice_id,
				'idPenawaran'=> $searchData[0]->id_penawaran,
				'idPejabat'=> $searchData[0]->id_pejabat,
                'invoiceNo'=> $searchData[0]->invoice_nomor,
                'invoiceDueTgl'=> $searchData[0]->invoice_due_date,
                'invoiceTgl'=> $searchData[0]->invoce_tgl,                
				'invoicePoNo'=> $searchData[0]->invoice_po_nomor,				
				'invoiceTtd'=> $searchData[0]->invoice_ttd,
				'invoicePejabat'=> $searchData[0]->invoice_pejabat,				
				'invoiceTelp'=> $searchData[0]->invoice_tlp,				
				'invoicePajak'=> $searchData[0]->invoice_pajak_persen,				
				'invoiceKe'=> $searchData[0]->invoice_ke,				
				'invoiceDiscount'=> $searchData[0]->invoice_nominal,				
				'jumlahData'=> $searchData[0]->jumlah,
                'data' => $searchData
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }
}
