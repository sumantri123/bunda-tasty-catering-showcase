<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier\Supplier;
use App\Models\JenisFile;
use App\Models\Penawaran\PO;
use App\Models\Pemesanan\Pemesanan;
use App\Models\Pemesanan\PemesananDetail;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use Session;
use Auth;
use Carbon\Carbon;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		        

        $data = array(
            'title' => 'Pemesanan Ke Supplier / Vendor ',
            'subtitle' => Session::get('subtitle'),
			'pass' => Session::get('passAdmin'),
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            'kode' => $kode,
			'idJb' => '',
			'status' => "new",
			'id' => '0',
        );         
		
		$supplier = Supplier::get();
                        
		//$returnHTML = view('pemesanan/list',compact('data'))->render();
		$returnHTML = view('pemesanan/index',compact('data','supplier'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    
	public function list(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Pemesanan Ke Supplier / Vendor',
            'subtitle' => Session::get('subtitle'),
			'pass' => Session::get('passAdmin'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'id' => $id,
			'status' => "new"
        );   				

        $returnHTML = view('pemesanan/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function upload(Request $request, $kode, $id)
    {		
		
        $data = array(
            'title' => 'Upload Dokumen Pendukung',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idPesan' => $id,
			
        );   				
		$jenisFile = JenisFile::get();
        $returnHTML = view('pemesanan/upload',compact('data','jenisFile'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function getData(Request $request, $id)
    {
        $data = DB::select(
					DB::raw('
						SELECT *
						FROM t_pemesanan as a 
						LEFT JOIN m_supplier as b on a.id_supplier = b.supplier_id
						LEFT JOIN t_jurnal_bagian as d on a.pesan_id = d.id_pesan
						LEFT JOIN 
							(
								select sum(harga) as total_harga, sum(qty) as total_qty,
								sum(pajak_nominal) as total_pajak, sum(total) as grand_total,
								id_pesan
								from t_pemesanan_detail                            								
								group by id_pesan
							) c on a.pesan_id = c.id_pesan
						where /*id_penawaran = '.$id.'                                                    
						and */a.id_kelas = "'.Session::get('kelas').'"
					')
				);

        //if($data) {
            return response()->json([
                'status'=>'oke',
                'data' => $data
                ]);
        /* } else {
            return response()->json(['status'=>'failed']);
        } */

    }
	
	public function getDataBP(Request $request, $id)
    {
        
        $po = PO::where('id_pesan','=',$id)->get();

        if($po) {
            return response()->json([
                'status'=>'oke',
                'data' => $po
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }

	public function add(Request $request, $kode, $idPenawaran)
    {		
		
        $data = array(
            'title' => 'Pemesanan Ke Supplier / Vendor ',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idPenawaran' => $idPenawaran,
			'idJb' => '',
			'idJurnalBagian' => '',
			'status' => "new"
        );   				
		
		$supplier = Supplier::get();

        $returnHTML = view('pemesanan/add',compact('data','supplier'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }

	public function edit(Request $request, $kode, $id, $idPenawaran, $id2)
    {		
		
        $data = array(
            'title' => 'Pemesanan Ke Supplier / Vendor',
            'subtitle' => Session::get('subtitle'),			
            'btnAdd' => 'Tambah',
			'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
			'kode' => $kode,
			'idPenawaran' => $idPenawaran,
			'idJb' => $id,
			'idJurnalBagian' => $id2,
			'status' => "edit"
        );   				
		$supplier = Supplier::get();
        $returnHTML = view('pemesanan/add',compact('data','supplier'))->render();
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
				$flag = 1; //0: dari menu jurnal bagian, 1: dari transaksi penjualan / pembelian
				
				$cekNomor = Pemesanan::where('id_kelas','=',Session::get('kelas'))
							->where('pesan_tahun','=',$year)
							->orderby('pesan_id','desc')							
							->limit(1)
							->get();
				
				if(count($cekNomor) > 0){
										
					$pecahNomor = explode("/",$cekNomor[0]->pesan_nomor);
					$lastUrut = $pecahNomor[0];
					$nextUrut = str_pad(($lastUrut+1),3,'0',STR_PAD_LEFT);
					$jenisDok = $pecahNomor[1];
					$kode = $pecahNomor[2];
					$bulan = $pecahNomor[3];
					$tahun = $pecahNomor[4];
										
					$nomer = $nextUrut.'/'.$jenisDok.'/'.$kode.'/'.$bulan.'/'.$tahun;					
					
				} else {
					
					$nomer = "001/PS/".$kode."/".$kodeBulan."/".$year;
				}
				
				//$pajakGlobal = $request->pajak_global;
				$pajakGlobal = 0;
				
				$insert = Pemesanan::create([
					"pesan_nomor"=> $nomer,
					"pesan_hal"=> $request->perihal,
					"pesan_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"id_supplier"=> $request->supplier,                    										
					"id_penawaran"=> $request->idPenawaran,                    										
					"pesan_pajak"=> $pajakGlobal,
					"pesan_tahun"=> date('Y', strtotime($request->tgl)),
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);
				
				// Insert di Jurnal Bagian
				$cekUrutan = Pemesanan::where('id_kelas','=',Session::get('kelas'))
							->where('pesan_tahun','=',$year)
							->orderby('pesan_id','desc')														
							->get();

				if(count($cekUrutan) > 0){
										
					$lastUrut = count($cekUrutan);					
					$ke = str_pad(($lastUrut+1),2,'0',STR_PAD_LEFT);					
					
					$nomerJB = "S-".$ke.date('dmy');					
					
				} else {
					$ke = "01";
					$nomerJB = "S-01".date('dmy');
				}	
				
				$insertJurnal = JurnalBagian::create([
					"jurnal_no"=> $nomerJB,
					"jurnal_keterangan"=> $request->perihal,
					"jurnal_tanggal"=> date('Y-m-d', strtotime($request->tgl)),
					"jurnal_bagian"=> "PM",                    
					"kode_transaksi"=> "PM",                    
					"id_pesan"=> $insert->pesan_id,                   
					"flag"=> $flag,
					"id_kelas"=> Session::get('kelas'),
					"dt_record"=> date("Y-m-d H:i:s"),
					"user_record"=> Session::get('login_as')
				]);

				if($insertJurnal) {
					DB::commit();
					return response()->json(['status'=>'insert_successful','id'=>$insert->pesan_id, 'no'=>$nomer, 'idJurnalBagian'=>$insertJurnal->jurnal_bagian_id]);                
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
	
	public function storeBP(Request $request)
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
					$path = "siantok/bukti_pendukung/";
					
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
						"id_jenis_file"=> $request->id_jenis_file,	
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
				
				$data = PemesananDetail::where('id_pesan','=',$id)->get();
							
				$update = Pemesanan::where('pesan_id', '=', $id)->update([                              
					"pesan_nomor"=> $request->no_bukti,
					"pesan_hal"=> $request->perihal,
					"pesan_tgl"=> date('Y-m-d', strtotime($request->tgl)),
					"id_supplier"=> $request->supplier,  
					"id_penawaran"=> $request->idPenawaran,                   										
					"pesan_pajak"=> $request->pajak_global,
					"pesan_tahun"=> date('Y', strtotime($request->tgl)),
					"id_kelas"=> Session::get('kelas'),
					"dt_modified"=> date("Y-m-d H:i:s"),
					"user_modified"=> Session::get('login_as')
				]);
				
				for($a=0; $a<count($data); $a++){
					
					$update2 = PemesananDetail::where('pesan_detail_id', '=', $data[$a]->pesan_detail_id)->update([                              
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
            $query = Pemesanan::find($id)->delete();
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
				$year = date('Y');
				$date = date('Y-m-d');

                $insert = PemesananDetail::create([                    
                    "id_pesan"=> $request->idJB,
					"id_perkiraan"=> $request->idPerkiraan,
					"id_barang_keluar"=> $request->idBarangKeluar,
                    "pesan_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"satuan"=> $request->satuan,
					"harga"=> $request->harga,
					"total"=> $newNominal,					
					"pajak_persen"=> $pajak,					
					"pajak_nominal"=> ($newNominal * $pajak)/100,					
                    "dt_record"=> date("Y-m-d H:i:s"),
                    "user_record"=> Session::get('login_as'),   
                ]);

				//Pembayaran Lunas
				$idJenisTransaksi = [1,2];
				$idPerkiraan = [$request->idPerkiraan,Session::get('11XXXKAS_ID')];

				for($a=0; $a<2; $a++){

					$insertJB = JurnalBagianDetail::create([
						"id_perkiraan"=> $idPerkiraan[$a],
						"id_jurnal_bagian"=> $request->idJurnalBagian,
						"id_jenis_transaksi"=> $idJenisTransaksi[$a],
						"jurnal_det_nominal"=> $newNominal,
						"dt_record"=> date("Y-m-d H:i:s"),
						"user_record"=> Session::get('login_as'),   
					]);

				}
    
                if($insert) {
                    return response()->json(['status'=>'insert_successful','id'=>$insert->pesan_detail_id]);                
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
	
                $update = PemesananDetail::where('pesan_detail_id', '=', $id)->update([                  
                    "id_pesan"=> $request->idJB,
                    "pesan_deskripsi"=> $request->deskripsi,
                    "qty"=> $request->qty,
					"harga"=> $request->harga,
					"total"=> $newNominal,
					"pajak_persen"=> $pajak,					
					"pajak_nominal"=> ($newNominal * $pajak)/100,						
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
			$query = PemesananDetail::find($id)->delete();
			
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

    public function totalDet(Request $request, $id)
    {
		$data = DB::select(
                DB::raw('
                    SELECT sum(harga) as total_harga, sum(total) as total, sum(pajak_nominal) as total_pajak, 
					count(id_pesan) as jumlah_data
                    FROM t_pemesanan_detail
                    where id_pesan = '.$id.'
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
        $id = $request->idJb;

		$searchData = DB::select(
                DB::raw('
                    SELECT *
                    FROM t_pemesanan as a 
                    LEFT JOIN t_pemesanan_detail as b on a.pesan_id = b.id_pesan
                    LEFT JOIN 
                        (
                            select count(id_pesan) as jumlah, id_pesan
                            from t_pemesanan_detail                            
                            where id_pesan = '.$id.'                            
							group by id_pesan
                        ) c on c.id_pesan = a.pesan_id
                    where pesan_id = '.$id.'                                                    
                    and id_kelas = "'.Session::get('kelas').'"                    
                    ORDER BY pesan_detail_id asc
                ')
            );

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'pesanId'=> $searchData[0]->pesan_id,
                'pesanNo'=> $searchData[0]->pesan_nomor,
                'pesanHal'=> $searchData[0]->pesan_hal,
                'pesanTgl'=> $searchData[0]->pesan_tgl,
                'idSup'=> $searchData[0]->id_supplier,				
				'pesanPajak'=> $searchData[0]->pesan_pajak,
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
			
			$LEditPerkiraan = DB::table('m_barang_keluar')				
							->orderby('m_barang_keluar_nama','asc')							
							->limit(10)
							->get();
			
        }else{                

			$LEditPerkiraan = DB::table('m_barang_keluar')														
							->where('id_lembaga','=',Session::get('idLembaga'))	
							->where('m_barang_keluar_nama', 'like','%'. $search . '%')																
							->limit(10)
							->get();          

        }

        $response = array();
        if($LEditPerkiraan->isEmpty()) {
                $response[] = array("value"=>"0","label"=>"Note.Kode Tidak Ada");
        } else {
            foreach($LEditPerkiraan as $EditPerkiraan){
                $response[] = array("value"=>$EditPerkiraan->id_perkiraan.'|'.$EditPerkiraan->m_barang_keluar_id,"label"=>$EditPerkiraan->m_barang_keluar_nama);
            }
        }

        return response()->json($response);
    }
}
