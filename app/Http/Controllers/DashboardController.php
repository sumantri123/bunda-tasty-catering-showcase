<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Penawaran\Penawaran;
use App\Models\Invoice\Invoice;
use App\Models\Kwitansi\Kwitansi;
use App\Models\Pemesanan\Pemesanan;
use App\Models\NilaiTukar;
use App\Models\Kelas;
use Auth;
use Session;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {	
        $date = Carbon::now();	
		$year = $date->format('Y');        		
		
		/* GET data from database */
		$LPenawaran = Penawaran::where('penawaran_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();
		$LInvoice = Invoice::where('invoice_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();
		$LKwitansi = Kwitansi::where('kw_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();
		$LPesanan = Pemesanan::where('pesan_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();		
		$DPenawaran = DB::select(
					DB::raw('
						SELECT count(penawaran_id) as total, penawaran_tahun
						FROM t_penawaran 
						where id_kelas = "'.Session::get('kelas').'" 
						group by penawaran_tahun desc
						limit 4
					')
				);
		$DCustomer = DB::select(
					DB::raw('						
						SELECT count(penawaran_id) as total, penawaran_tahun, customer_nama
						FROM t_penawaran a
						left join m_customer b on a.id_customer = b.customer_id						
						where penawaran_tahun = '.$year.' 
						and a.id_kelas = "'.Session::get('kelas').'" 
						group by penawaran_tahun desc, customer_nama
						order by total desc
						limit 5						
					')
				);
		$DSupplier = DB::select(
					DB::raw('						
						SELECT count(pesan_id) as total, pesan_tahun, supplier_nama
						FROM t_pemesanan a
						left join m_supplier b on a.id_supplier = b.supplier_id						
						where pesan_tahun = '.$year.'
						and a.id_kelas = "'.Session::get('kelas').'"
						group by pesan_tahun desc, supplier_nama
						order by total desc
						limit 5						
					')
				);		
		$DPenawaranSummary = DB::select(
					DB::raw('
						SELECT *
						FROM t_penawaran a
						left join m_customer b on a.id_customer = b.customer_id						
						where penawaran_tahun = '.$year.'
						and a.id_kelas = "'.Session::get('kelas').'"
					')
				);		
        		
		$data = array(
			'title' => "Dashboard Tahun ".$year,
            'now' => $date->format('d-m-Y'),
			'year' => $year,
			'totalPenawaran' => $LPenawaran,            
			'totalInvoice' => $LInvoice,            
			'totalKwitansi' => $LKwitansi,            
			'totalPesanan' => $LPesanan,            
			'dataPenawaran' => $DPenawaran,
			'dataCustomer' => $DCustomer,
			'dataSupplier' => $DSupplier,
			'dataSummary' => $DPenawaranSummary,
        ); 
			
        return view('frontend/page/dashboard/index', compact('data'));
        
    }                   
    
	public function detail(Request $request, $id)
    {
        $dataPenawaran = DB::select(
					DB::raw('
						SELECT *
						FROM t_penawaran as a
						left join m_customer b on a.id_customer = b.customer_id	
						left join (
							select sum(total) as total_penawaran, sum(pajak_nominal) as total_pajak, id_penawaran
							from t_penawaran_detail 
							where id_penawaran = '.$id.'						
							group by id_penawaran
						) c on a.penawaran_id = c.id_penawaran
						where penawaran_id = '.$id.'                                                    												
					')
				);
		
		$po = DB::select(
				DB::raw('
					SELECT *
					FROM t_dok_po a
					left join m_jenis_file b on a.id_jenis_file = b.jenis_file_id
					where a.id_penawaran = '.$id.' and jenis_file_id = 1
				')
			);

		$do = DB::select(
				DB::raw('
					SELECT *
					FROM t_delivery_order as a						
					where id_penawaran = '.$id.'                                                    												
				')
			);

		$invoice = DB::select(
				DB::raw('
					SELECT *
					FROM t_invoice as a						
					left join (
							select sum(total) as total_invoice, sum(pajak_nominal) as total_pajak, id_invoice
							from t_invoice_det 							
							group by id_invoice
						) d on a.invoice_id = d.id_invoice					
					where a.id_penawaran = '.$id.'                                                    												
				')
			);
		
		if(count($invoice) > 0){
			for($a=0; $a<count($invoice); $a++){

				$faktur[$a] = DB::select(
					DB::raw('
						SELECT *
						FROM t_dok_po a
						left join m_jenis_file b on a.id_jenis_file = b.jenis_file_id
						where a.id_invoice = '.$invoice[$a]->invoice_id.' and jenis_file_id = 2                                                   												
					')
				);
				
				$kw[$a] = DB::select(
					DB::raw('
						SELECT *
						FROM t_kwitansi as a											
						left join t_dok_po as b on a.kw_id = b.id_kw					
						where a.id_invoice = '.$invoice[$a]->invoice_id.'
					')
				);
				
				if(count($kw[$a]) > 0){
					for($b=0; $b<count($kw[$a]); $b++){
						$bt[$a] = DB::select(
							DB::raw('
								SELECT *
								FROM t_dok_po as a											
								left join m_jenis_file as b on a.id_jenis_file = b.jenis_file_id
								where a.id_kw = '.$kw[$a][$b]->kw_id.'
							')
						);
					}
				} else {
					$bt ="";
				}
			}
		} else {
			$faktur ="";
			$kw ="";
			
		}
		
		$supplier = DB::select(
				DB::raw('
					SELECT *
					FROM t_pemesanan as a						
					left join m_supplier as b on a.id_supplier = b.supplier_id
					left join (
							select sum(total) as total_pesanan, sum(pajak_nominal) as total_pajak, id_pesan
							from t_pemesanan_detail 							
							group by id_pesan
						) d on a.pesan_id = d.id_pesan					
					where a.id_penawaran = '.$id.'                                                    												
				')
			);
		
		if(count($supplier) > 0){
			for($c=0; $c<count($supplier); $c++){

				$dp[$c] = DB::select(
						DB::raw('
							SELECT *
							FROM t_dok_po as a											
							left join m_jenis_file as b on a.id_jenis_file = b.jenis_file_id
							where a.id_pesan = '.$supplier[$c]->pesan_id.'
						')
					);
			}
		} else {
			$dp ="";
		}
		
        return view('frontend/page/dashboard/detail', compact('dataPenawaran','po','do','invoice','faktur','kw','bt','supplier','dp'));
    }

    public function beranda()
    {
        
        $date = Carbon::now();	
		$year = $date->format('Y');
                      		
		$LPenawaran = Penawaran::where('penawaran_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();
		$LInvoice = Invoice::where('invoice_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();
		$LKwitansi = Kwitansi::where('kw_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();
		$LPesanan = Pemesanan::where('pesan_tahun','=',$year)
					->where('id_kelas','=',Session::get('kelas'))
					->count();		
		$DPenawaran = DB::select(
					DB::raw('
						SELECT count(penawaran_id) as total, penawaran_tahun
						FROM t_penawaran 
						where id_kelas = "'.Session::get('kelas').'" 
						group by penawaran_tahun desc
						limit 4
					')
				);
		$DCustomer = DB::select(
					DB::raw('						
						SELECT count(penawaran_id) as total, penawaran_tahun, customer_nama
						FROM t_penawaran a
						left join m_customer b on a.id_customer = b.customer_id						
						where penawaran_tahun = '.$year.' 
						and a.id_kelas = "'.Session::get('kelas').'" 
						group by penawaran_tahun desc, customer_nama
						order by total desc
						limit 5						
					')
				);
		$DSupplier = DB::select(
					DB::raw('						
						SELECT count(pesan_id) as total, pesan_tahun, supplier_nama
						FROM t_pemesanan a
						left join m_supplier b on a.id_supplier = b.supplier_id						
						where pesan_tahun = '.$year.'
						and a.id_kelas = "'.Session::get('kelas').'"
						group by pesan_tahun desc, supplier_nama
						order by total desc
						limit 5						
					')
				);		
		$DPenawaranSummary = DB::select(
					DB::raw('
						SELECT *
						FROM t_penawaran a
						left join m_customer b on a.id_customer = b.customer_id						
						where penawaran_tahun = '.$year.'
						and a.id_kelas = "'.Session::get('kelas').'"
					')
				);		
        		
		$data = array(
			'title' => "Dashboard Tahun ".$year,
            'now' => $date->format('d-m-Y'),
			'year' => $year,
			'totalPenawaran' => $LPenawaran,            
			'totalInvoice' => $LInvoice,            
			'totalKwitansi' => $LKwitansi,            
			'totalPesanan' => $LPesanan,            
			'dataPenawaran' => $DPenawaran,
			'dataCustomer' => $DCustomer,
			'dataSupplier' => $DSupplier,
			'dataSummary' => $DPenawaranSummary,
        ); 
		
		$returnHTML = view('frontend/page/dashboard/dashboard',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
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
}
