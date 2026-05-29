<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\UserSystemInfoHelper;
use App\Models\Customer\Customer;
use App\Models\Sosmed\Sosmed;
use App\Models\TCPlanner\TCPlanner;
use App\Models\TCPlanner\MCPlanner;
use App\Models\TCPlanner\TCIdea;
use Auth;
use Session;
use DB;

class ContentPlannerController extends Controller
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
			'title' => 'Content Planner',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
			'classFormSelect2' => 'single-select',			
        );       
		
		$MCPlanner = MCPlanner::where('id_kelas','=', Session::get('kelas'))->get();
		$Sosmed = Sosmed::where('id_kelas','=', Session::get('kelas'))->where('publish','=', 'y')->get();
		$TCPlanner = TCPlanner::where('id_kelas','=', Session::get('kelas'))->get();		

		$returnHTML = view('content_planner/index',compact('data','TCPlanner','MCPlanner','Sosmed'))->render();
		return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData($param)
    {
		$data = explode("-",$param);
		$month = $data[0];
		$jenisSosmed = $data[1];
		$year = $data[2];
		$day = $data[3];		
		$date = $year.'-'.$month.'-'.$day;
	
        $cs = DB::select(
					DB::raw('
						SELECT *
						FROM t_cplanner as a 		
						left join m_kat_cplanner as b on a.id_m_kat_cplanner = b.m_cplanner_id
						left join m_sosmed as c on a.id_sosmed = c.sosmed_id
						where a.id_kelas = "'.Session::get('kelas').'"
						and month(datestart) = "'.$month.'"
						and year(datestart) = "'.$year.'"
						and datestart >= "'.$date.'"
						and id_sosmed = '.$jenisSosmed.'
						ORDER BY datestart asc, sosmed_jenis asc
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

	public function getDataIdea($param)
    {
		$data = explode("-",$param);
		$month = $data[0];
		$jenisSosmed = $data[1];
		$year = $data[2];
		$day = $data[3];		
		$date = $year.'-'.$month.'-'.$day;
	
        $cs = DB::select(
					DB::raw('
						SELECT *
						FROM t_cidea as a 		
						left join m_sosmed as c on a.id_sosmed = c.sosmed_id						
						where a.id_kelas = "'.Session::get('kelas').'"
						and month(tenggat_waktu) = "'.$month.'"
						and year(tenggat_waktu) = "'.$year.'"						
						and id_sosmed = '.$jenisSosmed.'
						ORDER BY tenggat_waktu asc, sosmed_jenis asc
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

	public function getDataGrafik($param)
    {
		$data = explode("-",$param);
		$month = $data[0];
		$jenisSosmed = $data[1];
		$year = $data[2];		
				
		/* 1. GET Info basic From Tiktok */		
		if(Session::get('accessToken') != ""){
			$headers1 = [	
				'Authorization: Bearer '.Session::get('accessToken'),
			];				

			$url1 = "https://open.tiktokapis.com/v2/user/info/?fields=open_id,union_id,avatar_url,display_name,follower_count";		

			$client1 = curl_init();
			curl_setopt($client1, CURLOPT_URL, $url1);
			curl_setopt($client1, CURLOPT_HTTPHEADER, $headers1); 				
			curl_setopt($client1, CURLOPT_RETURNTRANSFER, 1);		

			$response1 = curl_exec($client1);
			$result1 = json_decode($response1);		
			$follower = $result1->data->user->follower_count;	
			
		} else {
			$follower = 0;	
		}			
		
		$jmlhCntnAll = DB::select(
							DB::raw('
								SELECT count(t_cplanner_id) as total
								FROM t_cplanner 			
								where id_kelas = "'.Session::get('kelas').'"								
								and year(datestart) = "'.$year.'"								
								and id_sosmed = '.$jenisSosmed.'								
							')
						);

		$jumlahContent = DB::select(
							DB::raw('
								SELECT *
								FROM t_cplanner as a 		
								left join m_kat_cplanner as b on a.id_m_kat_cplanner = b.m_cplanner_id
								left join m_sosmed as c on a.id_sosmed = c.sosmed_id
								where a.id_kelas = "'.Session::get('kelas').'"
								and month(datestart) = "'.$month.'"
								and year(datestart) = "'.$year.'"								
								and id_sosmed = '.$jenisSosmed.'
								ORDER BY datestart asc, sosmed_jenis asc
							')
						);

		$jumlahIdea = DB::select(
							DB::raw('
								SELECT *
								FROM t_cidea as a 										
								left join m_sosmed as c on a.id_sosmed = c.sosmed_id
								where a.id_kelas = "'.Session::get('kelas').'"
								and month(tenggat_waktu) = "'.$month.'"
								and year(tenggat_waktu) = "'.$year.'"								
								and id_sosmed = '.$jenisSosmed.'
								ORDER BY tenggat_waktu asc, sosmed_jenis asc
							')
						);
		
		$tigaBesarKatCP = DB::select(
					DB::raw('
						select count(t_cplanner_id) as total, m_cplanner_nama, m_cplanner_id
						from t_cplanner as a
						left join m_kat_cplanner as b on a.id_m_kat_cplanner = b.m_cplanner_id
						where year(datestart) = "'.$year.'" 
						and a.id_kelas = "'.Session::get('kelas').'"	
						and id_sosmed = '.$jenisSosmed.'
						group by m_cplanner_nama, m_cplanner_id
						order by total desc, m_cplanner_nama asc
						limit 3
					')
				);

		$dataKatName = [];
		$dataPersen = [];
		
		//if(count($tigaBesarKatCP) > 0){

			for($a=0; $a<3; $a++){

				$persenKat[$a] = isset($tigaBesarKatCP[$a]->total) ? $tigaBesarKatCP[$a]->total : 0;
				$persen[$a] = ($persenKat[$a] == 0) ? "0" : ($persenKat[$a] / $jmlhCntnAll[0]->total )*100;
				$idMKat[$a] = isset($tigaBesarKatCP[$a]->m_cplanner_id) ? $tigaBesarKatCP[$a]->m_cplanner_id : 0;

				$name[$a] = isset($tigaBesarKatCP[$a]->m_cplanner_nama) ? $tigaBesarKatCP[$a]->m_cplanner_nama : "-";				
				array_push($dataKatName, $name[$a]);			
				array_push($dataPersen, round($persen[$a],2));			

				$totPerKat = DB::select(
						DB::raw('
							SELECT month(datestart) as bulan_number,
							year(datestart) as year, count(t_cplanner_id) as total
							FROM t_cplanner 
							where year(datestart) = "'.$year.'"
							and id_sosmed = '.$jenisSosmed.'
							and id_m_kat_cplanner = '.$idMKat[$a].'
							group by year, bulan_number
							order by bulan_number asc
						')
					);

				for($c=0; $c<count($totPerKat); $c++){
				
					$value[$a][ $totPerKat[$c]->bulan_number ] = $totPerKat[$c]->total;
					
				}
				
				$dataKat[$a] = [];
				for($d=1; $d<13; $d++){

					$valueBar[$a] = isset($value[$a][$d]) ? $value[$a][$d]:0;
					array_push($dataKat[$a], $valueBar[$a]);
					
				}

			}

		/* } else {
			$dataKat = [];
		} */

        $grafik1 = DB::select(
					DB::raw('
						select m_cplanner_nama as name, count(t_cplanner_id) as y, m_cplanner_id
						from t_cplanner as a
						left join m_kat_cplanner as b on a.id_m_kat_cplanner = b.m_cplanner_id
						where a.id_kelas = "'.Session::get('kelas').'"
						and month(datestart) = "'.$month.'"
						and year(datestart) = "'.$year.'"						
						and id_sosmed = '.$jenisSosmed.'
						group by m_cplanner_nama, m_cplanner_id
						order by y desc
						limit 5
					')
				);		

		$grafik2 = DB::select(
					DB::raw('
						SELECT month(datestart) as bulan_number,
						year(datestart) as year, count(t_cplanner_id) as total
						FROM t_cplanner
						where year(datestart) = "'.$year.'"
						and id_sosmed = '.$jenisSosmed.'
						group by year, bulan_number
						order by bulan_number asc
					')
				);

		for($a=0; $a<count($grafik2); $a++){
			
			$value[ $grafik2[$a]->bulan_number ] = $grafik2[$a]->total;
			
		}
		
		$data2 = [];
		for($a=1; $a<13; $a++){

			$valueBar = isset($value[$a]) ? $value[$a]:0;
			array_push($data2, $valueBar);
			
		}
		
		return response()->json([			
			'data' => $grafik1, 
			'data2' => $data2,
			'data3' => $dataKat,
			'dataNameKat' => $dataKatName,
			'dataPersen' => $dataPersen,
			'jumlahContent' => count($jumlahContent),
			'jumlahIdea' => count($jumlahIdea),
			'follower' => $follower,
		]);        

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
            
            DB::beginTransaction();

            try {
				$dateNew = date("Y-m-d", strtotime($request->tgl_event));
                $insert = TCPlanner::create([
                    "id_m_kat_cplanner"=> $request->kat,
                    "datestart"=> $dateNew,								
					"id_kelas"=> Session::get('kelas'),                    
                    "jamstart"=> $request->time,
					"detail"=> $request->event_nama,                    
					"id_sosmed"=> $request->sosmed,                    
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

	public function store2(Request $request)
    {
        if($request->ajax()){
            
            DB::beginTransaction();

            try {
				$dateNew = date("Y-m-d", strtotime($request->tenggat_waktu));
                $insert = TCIdea::create([
                    "deskripsi"=> $request->idea,
                    "url_inspirasi"=> $request->url_inspirasi,							
					"id_kelas"=> Session::get('kelas'),                    
                    "status"=> $request->status2,
					"pic"=> $request->pic,
					"tenggat_waktu"=> $dateNew,
					"url_file"=> $request->url_file,
					"id_sosmed"=> $request->sosmed2,                    
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
			$dateNew = date("Y-m-d", strtotime($request->tgl_event));
            $update = TCPlanner::where('t_cplanner_id', '=', $id)->update([
				"detail"=> $request->event_nama, 
				"jamstart"=> $request->time,								
				"id_m_kat_cplanner"=> $request->kat,
				"datestart"=> $dateNew,	
				"id_sosmed"=> $request->sosmed,                    				
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

	public function update2(Request $request, $id)
    {
        if($request->ajax()){          
			$dateNew = date("Y-m-d", strtotime($request->tenggat_waktu));
            $update = TCIdea::where('t_cidea_id', '=', $id)->update([
				"deskripsi"=> $request->idea,
				"url_inspirasi"=> $request->url_inspirasi,											
				"status"=> $request->status2,
				"pic"=> $request->pic,
				"tenggat_waktu"=> $dateNew,
				"url_file"=> $request->url_file,
				"id_sosmed"=> $request->sosmed2,                     				
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

    public function destroy(Request $request, $param)
    {
		$data = explode("-",$param);
		$id = $data[0];
		$jenis = $data[1];		

        if($request->ajax()){
			if($jenis == 1){
				$query = TCPlanner::find($id)->delete();
			} else {
				$query = TCIdea::find($id)->delete();
			}

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
