<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\Terbilang;
use App\Models\Customer\Customer;
use App\Models\Penawaran\PO;
use App\Models\Penawaran\Penawaran;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceDetail;
use App\Models\Kwitansi\Kwitansi;
use Session;
use Auth;
use Carbon\Carbon;

class KwitansiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
	use Terbilang;
    public function index($kode)
    {		        

        $data = array(
            'title' => 'Kwitansi',
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
                        
		$returnHTML = view('kwitansi/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    		
	public function upload(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Upload Bukti Transfer',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idKw' => $id,
			
        );   				

        $returnHTML = view('kwitansi/upload',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }	

	public function getDataList()
    {
		
		$kw = DB::select(
                DB::raw('
                   select a.*, b.*
					from t_invoice as a
					left join t_kwitansi b on a.invoice_id = b.id_invoice
					order by invoice_id desc
                ')
            );
			
        if($kw) {
            return response()->json([
                'status'=>'oke',
                'data' => $kw
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	// Get Data Kwitansi Tanpa Invoice
	public function getDataKW()
    {
        $kw = Kwitansi::whereNull('id_invoice')->get();        

        if($kw) {
            return response()->json([
                'status'=>'oke',
                'data' => $kw
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
	
	public function getDataBT(Request $request, $id)
    {
        
        $po = PO::where('id_jenis_file','=',6)
			->where('id_kw','=',$id)->get();

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
					select a.*, d.customer_nama as kw_company
					from t_kwitansi as a
					left join t_invoice b on a.id_invoice = b.invoice_id
					left join t_penawaran c on b.id_penawaran = c.penawaran_id
					left join m_customer d on c.id_customer = d.customer_id
					where kw_id = '.$id.'
                ')
            );
        return view('kwitansi.cetak', compact('data'));
    }
	
	public function cetakTanpaInvoice(Request $request, $id)
    {
        
		$data = DB::select(
                DB::raw('
					select a.*
					from t_kwitansi as a
					left join t_invoice b on a.id_invoice = b.invoice_id
					left join t_penawaran c on b.id_penawaran = c.penawaran_id					
					where kw_id = '.$id.'
                ')
            );
        return view('kwitansi.cetak', compact('data'));
    }

	public function kwtoninvoice($kode)
    {		        

        $data = array(
            'title' => 'Kwitansi Tanpa Invoice',
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
                        
		$returnHTML = view('kwitansi/kwt',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }   
	
	public function addKWNonInvoice(Request $request, $kode)
    {		
		
        $data = array(
            'title' => 'Kwitansi Tanpa Invoice',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,			
			'status' => "new"
        );   				
		$customer = Customer::get();
        $returnHTML = view('kwitansi/addKwNonInvoice',compact('data','customer'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }
	
	public function addKwi(Request $request, $kode, $id)
    {		
 	
		$dataInvoice = DB::select(
                DB::raw('
					SELECT *
					FROM t_invoice as a						
					left join (
							select sum(total) as total_invoice, sum(pajak_nominal) as total_pajak, id_invoice
							from t_invoice_det 							
							group by id_invoice
						) d on a.invoice_id = d.id_invoice					
					where a.invoice_id = '.$id.' 
                ')
            );			
		//$customer = Customer::get();

		$data = array(
            'title' => 'Kwitansi Dengan Invoice',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idInvoice' => $id,			
			'status' => "new",
			'totalInvoice' => ($dataInvoice[0]->total_invoice - $dataInvoice[0]->total_pajak),
			'invoicePajakPersen' => $dataInvoice[0]->invoice_pajak_persen,
			'totalPajak' => $dataInvoice[0]->total_pajak,
			'terbilang' => ucwords($this->pembilang($dataInvoice[0]->total_invoice).' rupiah'),
        ); 

        $returnHTML = view('kwitansi/addKwInvoice',compact('data','dataInvoice'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }
	
	public function editKwi(Request $request, $kode, $id)
    {		
		
		$dataInvoice = DB::select(
                DB::raw('
					SELECT *
					FROM t_kwitansi
					where kw_id = '.$id.' 
                ')
            );		
			
        $data = array(
            'title' => 'Kwitansi Dengan Invoice',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,				
			'totalInvoice' => ( $dataInvoice[0]->kw_nominal - $dataInvoice[0]->kw_pajak_nominal),
			'invoicePajakPersen' => $dataInvoice[0]->kw_pajak_persen,
			'totalPajak' => $dataInvoice[0]->kw_pajak_nominal,			
			'idKwitansi' => $id,
			'terbilang' => ucwords($this->pembilang($dataInvoice[0]->kw_terbilang)),
			'status' => "edit"
        );   				
		$customer = Customer::get();
        $returnHTML = view('kwitansi/addKwInvoice',compact('data','customer','dataInvoice'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }	
	
	public function edit(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Kwitansi Tanpa Invoice',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,			
			'idKwitansi' => $id,
			'status' => "edit"
        );   				
		$customer = Customer::get();
        $returnHTML = view('kwitansi/addKwNonInvoice',compact('data','customer'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }	

    public function store(Request $request)
    {
        if($request->ajax()){
                
			DB::beginTransaction();

			try {
				
				if($request->idKw == "" ) {
					
					$year = date('Y', strtotime($request->tgl));
					$month = date('n', strtotime($request->tgl));
					$kode = base64_decode($request->bagian);
					$array_bln	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
					$kodeBulan = $array_bln[$month];
					
					$cekNomor = Kwitansi::where('id_kelas','=',Session::get('kelas'))
								->where('kw_tahun','=',$year)
								->orderby('kw_id','desc')							
								->limit(1)
								->get();
					
					if(count($cekNomor) > 0){
											
						$pecahNomor = explode("/",$cekNomor[0]->kw_nomor);
						$lastUrut = $pecahNomor[0];
						$nextUrut = str_pad(($lastUrut+1),3,'0',STR_PAD_LEFT);
						$jenisDok = $pecahNomor[1];
						$kode = $pecahNomor[2];
						$bulan = $pecahNomor[3];
						$tahun = $pecahNomor[4];
						
						$nomer = $nextUrut.'/'.$jenisDok.'/'.$kode.'/'.$bulan.'/'.$tahun;					
						
					} else {
						
						$nomer = "001/KW/".$kode."/".$kodeBulan."/".$year;
					}			
					
					$newNominal = $request->nominal;
					$pajak = $request->pajak_persen;
					
					$insert = Kwitansi::create([
						"kw_nomor"=> $nomer,					
						"kw_tgl"=> date('Y-m-d', strtotime($request->tgl)),
						"id_customer"=> $request->company_id,
						"kw_company"=> $request->company,
						"kw_deskripsi"=> $request->deskripsi,                    
						"kw_terbilang"=> $request->terbilang,                    
						"kw_nominal"=> $newNominal,                    
						"kw_pajak_persen"=> $pajak,                    
						"kw_pajak_nominal"=> ($newNominal * $pajak)/100,
						"kw_tahun"=> date('Y', strtotime($request->tgl)),					
						"kw_ttd"=> $request->ttd,   
						"id_kelas"=> Session::get('kelas'),
						"dt_record"=> date("Y-m-d H:i:s"),
						"user_record"=> Session::get('login_as')
					]);

					if($insert) {
						DB::commit();
						return response()->json(['status'=>'insert_successful','id'=>$insert->kw_id, 'no'=>$nomer]);                
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

	public function storeBT(Request $request)
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
					$path = "siantok/bukti_tf/";
					
					$request->file('file')-> move($path, $nama_file);
					$insert = PO::create([						
						"id_kw"=> $request->id,				
						"dt_record"=> date("Y-m-d H:i:s"),
						"file_size"=> $size,						
						"user_record"=> Session::get('login_as'),
						"file_name"=> $nama_file,
						"file_name_ori"=> $nama_file_ori,
						"file_path"=> $path,
						"file_exe"=> $ext,
						"id_jenis_file"=> 6,
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

    public function update(Request $request, $id)
    {
        if($request->ajax()){ 

			try {
				
				$newNominal = $request->nominal;
				$pajak = $request->pajak_persen;
				
				$update = Kwitansi::where('kw_id', '=', $id)->update([ 
					"kw_nomor"=> $request->no_bukti,					
					"kw_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"id_customer"=> $request->company_id,
					"kw_company"=> $request->company,
					"kw_deskripsi"=> $request->deskripsi,                    
					"kw_terbilang"=> $request->terbilang,                    
					"kw_nominal"=> $newNominal,                    
					"kw_pajak_persen"=> $pajak,                    
					"kw_pajak_nominal"=> ($newNominal * $pajak)/100,
					"kw_tahun"=> date('Y', strtotime($request->tgl)),					
					"kw_ttd"=> $request->ttd,   
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
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
            $query = Kwitansi::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
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
        $idKw = $request->idKw;
		
		$searchData = Kwitansi::where('kw_id','=',$idKw)->get();		

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'kwId'=> $searchData[0]->kw_id,	
				'idCustomer'=> $searchData[0]->id_customer,			
                'kwNo'=> $searchData[0]->kw_nomor,
                'kwDeskripsi'=> $searchData[0]->kw_deskripsi,
                'kwTgl'=> $searchData[0]->kw_tgl,                
				'kwCompany'=> $searchData[0]->kw_company,				
				'kwNominal'=> $searchData[0]->kw_nominal,
				'kwTerbilang'=> $searchData[0]->kw_terbilang,				
				'kwPajakPersen'=> $searchData[0]->kw_pajak_persen,				
				'kwPajakRp'=> $searchData[0]->kw_pajak_nominal,				
				'kwTtd'=> $searchData[0]->kw_ttd,								
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }
	
	
	public function storeKw(Request $request)
    {
        if($request->ajax()){
                
			DB::beginTransaction();

			try {
				
				$year = date('Y', strtotime($request->tgl));
				$month = date('n', strtotime($request->tgl));
				$kode = base64_decode($request->bagian);
				$array_bln	= array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
				$kodeBulan = $array_bln[$month];
				
				$cekNomor = Kwitansi::where('id_kelas','=',Session::get('kelas'))
							->where('kw_tahun','=',$year)
							->orderby('kw_id','desc')							
							->limit(1)
							->get();
				
				if(count($cekNomor) > 0){
										
					$pecahNomor = explode("/",$cekNomor[0]->kw_nomor);
					$lastUrut = $pecahNomor[0];
					$nextUrut = str_pad(($lastUrut+1),3,'0',STR_PAD_LEFT);
					$jenisDok = $pecahNomor[1];
					$kode = $pecahNomor[2];
					$bulan = $pecahNomor[3];
					$tahun = $pecahNomor[4];
					
					$nomer = $nextUrut.'/'.$jenisDok.'/'.$kode.'/'.$bulan.'/'.$tahun;					
					
				} else {
					
					$nomer = "001/KW/".$kode."/".$kodeBulan."/".$year;
				}			
				
				$newNominal = $request->nominal;
				$pajak = $request->pajak_persen;
				
				$insert = Kwitansi::create([
					"kw_nomor"=> $nomer,					
					"kw_tgl"=> date('Y-m-d', strtotime($request->tgl)),					
					"id_invoice"=> $request->idInvoice,
					"id_customer"=> $request->company_id,
					"kw_deskripsi"=> $request->deskripsi,                    
					"kw_terbilang"=> $request->terbilang,                    
					"kw_nominal"=> $newNominal,                    
					"kw_pajak_persen"=> $pajak,                    
					"kw_pajak_nominal"=> ($newNominal * $pajak)/100,
					"kw_tahun"=> date('Y', strtotime($request->tgl)),					
					"kw_ttd"=> $request->ttd,   
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);

				if($insert) {
					DB::commit();
					return response()->json(['status'=>'insert_successful','id'=>$insert->kw_id, 'no'=>$nomer]);                
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
}
