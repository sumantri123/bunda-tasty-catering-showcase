<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\UserSystemInfoHelper;
use App\Models\Customer\Customer;
use App\Models\Dashboard\SosmedContentFile;
use App\Models\Sosmed\Sosmed;
use App\Models\TCPlanner\TCPlanner;
use App\Models\TCPlanner\MCPlanner;
use App\Models\TCPlanner\TCIdea;
use Auth;
use Session;
use DB;

class DashboardSosmedController extends Controller
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
			'title' => 'Dashboard Sosial Media',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
			'classFormSelect2' => 'single-select',			
        );  
				
		$Sosmed = Sosmed::where('publish','=', 'y')->get();		

		$returnHTML = view('dashboard_sosmed/index',compact('data','Sosmed'))->render();
		return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getDataTiktok(Request $request, $param)
    {
				
		if(Session::get('accessToken') != ""){
						
			
			/* 1. GET Info basic From Tiktok */		
			$headers1 = [	
				'Authorization: Bearer '.Session::get('accessToken'),
			];				

			$url1 = "https://open.tiktokapis.com/v2/user/info/?fields=open_id,profile_deep_link,likes_count,union_id,avatar_url,display_name,following_count,follower_count,bio_description,video_count";		

			$client1 = curl_init();
			curl_setopt($client1, CURLOPT_URL, $url1);
			curl_setopt($client1, CURLOPT_HTTPHEADER, $headers1); 				
			curl_setopt($client1, CURLOPT_RETURNTRANSFER, 1);		

			$response1 = curl_exec($client1);
			$result1 = json_decode($response1);	
			
			$follower = $result1->data->user->follower_count;	
			$following = $result1->data->user->following_count;	
			$like = $result1->data->user->likes_count;	
			$displayName = $result1->data->user->display_name;	
			$avatarUrl = $result1->data->user->avatar_url;	
			$desc = $result1->data->user->bio_description;	
			$videoCount = $result1->data->user->video_count;	
			$profileLink = $result1->data->user->profile_deep_link;				
			
			
			/* 2. GET List Video From Tiktok */		
			$headers = [
				'Authorization: Bearer '.Session::get('accessToken'),
				'Content-Type: application/json',				
			];				
			
			$data = [				
				'max_count' => 9
			];
		
			$url = "https://open.tiktokapis.com/v2/video/list/?fields=share_url,create_time,video_description,cover_image_url,id,title,duration,height,width,embed_html,embed_link,like_count,comment_count,share_count,view_count";

			$client = curl_init();
			curl_setopt($client, CURLOPT_URL, $url);			
			curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);		
			curl_setopt($client, CURLOPT_POSTFIELDS,json_encode($data));
			
			$response = curl_exec($client);
			$result = json_decode($response);									
			
			/* $embed = $result->data->videos[0]->embed_html;	
			$embedLink0 = $result->data->videos[0]->cover_image_url;	
			$embedLink1 = $result->data->videos[1]->cover_image_url;	 */
			
			$data['data'] = '';						
				$data['data'] .= '<div class="row">';	
					$data['data'] .= '<div class="col-12 col-lg-4">';			
						$data['data'] .= '<div class="card bg-gradient-deepblue radius-10">';				
							$data['data'] .= '<div class="card-body text-center">';
								$data['data'] .= '<div class="p-4 border radius-15" style="background-color:#fff">';
									$data['data'] .= '<img src="'.$avatarUrl.'" width="110" height="110" class="rounded-circle shadow p-1 bg-white" alt="">';
									$data['data'] .= '<h5 class="mb-0 mt-4 mb-2">'.$displayName.'</h5>';
									$data['data'] .= '<p class="mb-3"><small>'.$desc.'</small></p>';
									$data['data'] .= '<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-0 row-group text-center border-top">';
										$data['data'] .= '<div class="col">';
											$data['data'] .= '<div class="p-3">';
												$data['data'] .= '<h5 class="mb-0">'.$following.'</h5>';
												$data['data'] .= '<small class="mb-0">Mengikuti</small>';
											$data['data'] .= '</div>';				
										$data['data'] .= '</div>';	
										$data['data'] .= '<div class="col">';
											$data['data'] .= '<div class="p-3">';
												$data['data'] .= '<h5 class="mb-0">'.$follower.'</h5>';
												$data['data'] .= '<small class="mb-0">Pengikut</small>';
											$data['data'] .= '</div>';				
										$data['data'] .= '</div>';								
									$data['data'] .= '</div><br>';	
									$data['data'] .= '<div class="d-grid"> <a href="'.$profileLink.'" target="_blank" class="btn btn-sm bg-gradient-deepblue text-white radius-15">Open Tiktok</a></div>';							
								$data['data'] .= '</div>';
							$data['data'] .= '</div>';
						$data['data'] .= '</div>';	

						$data['data'] .= '<div class="card radius-10 overflow-hidden border-success">';				
							$data['data'] .= '<div class="card-body">';
								$data['data'] .= '<div class="d-flex align-items-center">';
									$data['data'] .= '<div class="">';
										$data['data'] .= '<p class="mb-1 text-success"><span style="font-size:17px" id="gfkContentCalender_0"></span></p>';
										$data['data'] .= '<span style="font-size:13px" id="gfkPersenCalender_0"></span>';
									$data['data'] .= '</div>';							
								$data['data'] .= '</div>';
							$data['data'] .= '</div>';
							$data['data'] .= '<div class="chartContent" id="chart12"></div>';
						$data['data'] .= '</div>';
						
						$data['data'] .= '<div class="card radius-10 overflow-hidden border-primary">';				
							$data['data'] .= '<div class="card-body">';
								$data['data'] .= '<div class="d-flex align-items-center">';
									$data['data'] .= '<div class="">';
										$data['data'] .= '<p class="mb-1 text-primary"><span style="font-size:17px" id="gfkContentCalender_1"></span></p>';
										$data['data'] .= '<span style="font-size:13px" id="gfkPersenCalender_1"></span>';
									$data['data'] .= '</div>';
								$data['data'] .= '</div>';
							$data['data'] .= '</div>';
							$data['data'] .= '<div class="chartContent" id="chart13"></div>';
						$data['data'] .= '</div>';
						
						$data['data'] .= '<div class="card radius-10 overflow-hidden border-danger">';				
							$data['data'] .= '<div class="card-body">';
								$data['data'] .= '<div class="d-flex align-items-center">';
									$data['data'] .= '<div class="">';
										$data['data'] .= '<p class="mb-1"><span style="color:#f411f4; font-size:17px" id="gfkContentCalender_2"></span></p>';
										$data['data'] .= '<span style="font-size:13px" id="gfkPersenCalender_2"></span>';
									$data['data'] .= '</div>';
								$data['data'] .= '</div>';
							$data['data'] .= '</div>';
							$data['data'] .= '<div class="chartContent" id="chart14"></div>';
						$data['data'] .= '</div>';
						
					$data['data'] .= '</div>';
					
					$data['data'] .= '<div class="col-12 col-lg-8">';
						$data['data'] .= '<div class="row">';
							$data['data'] .= '<div class="col-12 col-lg-6">';
								$data['data'] .= '<div class="card radius-10 bg-gradient-ohhappiness">';
									$data['data'] .= '<div class="card-body">';
										$data['data'] .= '<div class="d-flex align-items-center">';
											$data['data'] .= '<div>';
												$data['data'] .= '<p class="mb-0 text-white">Total Suka</p>';
												$data['data'] .= '<h4 class="my-1 text-white">'.$like.' Suka</h4>';
												$data['data'] .= '<p class="mb-0 font-13 text-white"><i class="bx bxs-up-arrow align-middle"></i> From Sosial Media Tiktok</p>';
											$data['data'] .= '</div>';
											$data['data'] .= '<div class="widgets-icons bg-white text-success ms-auto"><i class="bx bx-like"></i></div>';
										$data['data'] .= '</div>';
									$data['data'] .= '</div>';
								$data['data'] .= '</div>';
							$data['data'] .= '</div>';
					
							$data['data'] .= '<div class="col-12 col-lg-6">';
								$data['data'] .= '<div class="card radius-10 bg-gradient-orange">';
									$data['data'] .= '<div class="card-body">';
										$data['data'] .= '<div class="d-flex align-items-center">';
											$data['data'] .= '<div>';
												$data['data'] .= '<p class="mb-0 text-white">Total Content</p>';
												$data['data'] .= '<h4 class="my-1 text-white">'.$videoCount.' Video</h4>';
												$data['data'] .= '<p class="mb-0 font-13 text-white"><i class="bx bxs-up-arrow align-middle"></i> From Sosial Media Tiktok</p>';
											$data['data'] .= '</div>';
											$data['data'] .= '<div class="widgets-icons bg-white text-warning ms-auto"><i class="bx bx-video"></i></div>';
										$data['data'] .= '</div>';
									$data['data'] .= '</div>';
								$data['data'] .= '</div>';	
							$data['data'] .= '</div>';
							
							$data['data'] .= '<div class="card radius-10">';
								$data['data'] .= '<div class="card-body">';
									$data['data'] .= '<div class="d-flex align-items-center">';
										$data['data'] .= '<div>';
											$data['data'] .= '<h5 class="mb-1">Content Tiktok</h5>';
											$data['data'] .= '<p class="mb-0 font-13 text-secondary"><i class="bx bxs-calendar"></i>Last 9 contents</p>';
										$data['data'] .= '</div>';
										$data['data'] .= '<div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>';
										$data['data'] .= '</div>';
									$data['data'] .= '</div>';
								$data['data'] .= '</div>';

								$data['data'] .= '<div class="row row-cols-1 row-cols-md-1 row-cols-lg-3 row-cols-xl-3" style="padding-left:20px">';								
																

										for($a=0; $a<count($result->data->videos); $a++){
											$data['data'] .= '<div class="col video-post">';
												$data['data'] .= '<div class="card bg-gradient-deepblue text-white" style="padding:7px">';
													$data['data'] .= '<img src="'.$result->data->videos[$a]->cover_image_url.'" class="card-img" alt="...">';	
													$data['data'] .= '<div class="card-img-overlay">';														
														$data['data'] .= '<div class="text-center" style="margin-top:50px;font-size:75px">';
															$data['data'] .= '<a href="javascript:void(0)" onclick="showVideo(\''.$result->data->videos[$a]->embed_link.'\')">';
//															$data['data'] .= '<a class="video-btn" data-toggle="modal" data-src="'.$result->data->videos[$a]->embed_link.'" data-target="#myModal" href="javascript:void(0)">';
																$data['data'] .= '<i class="fadeIn animated bx bx-caret-right-circle"></i>';
															$data['data'] .= '</a>';
														$data['data'] .= '</div>';
													$data['data'] .= '</div>';
												$data['data'] .= '</div>';
											$data['data'] .= '</div>';
										}

									
								$data['data'] .= '</div>';
							$data['data'] .= '</div>';
							
						$data['data'] .= '</div>';
					$data['data'] .= '</div>';
				$data['data'] .= '</div>';

		} else {

			$data['data'] = '';		
		
			$data['data'] .= '<div class="alert alert-primary border-0 bg-primary alert-dismissible fade show py-2">';	
				$data['data'] .= '<div class="d-flex align-items-center">';	
					$data['data'] .= '<div class="font-35 text-white"><i class="bx bxs-message-square-x"></i></div>';	
						$data['data'] .= '<div class="ms-3">';	
							$data['data'] .= '<h6 class="mb-0 text-white">INFORMATION</h6>';								
							$data['data'] .= '<div class="text-white">Dashboard Tidak Bisa Ditampilkan, Karena Anda Login Tidak Menggunakan Akun Sosial Media</div>';	
						$data['data'] .= '</div>';
					$data['data'] .= '</div>';
				$data['data'] .= '</div>';							
			$data['data'] .= '</div>';	

		}	
		
		return response()->json(['data' => $data['data']]);
    }

	public function getDataGrafikTiktok($param)
    {		
		
		$jenisSosmed = $param;							
		$grafik0 = DB::select(
				DB::raw('
					select distinct month(date) as bulan, year(date) as tahun, max(follower) as follower
					from t_dashboard_sosmed
					where id_sosmed = 4
					group by bulan, tahun
					order by tahun asc, bulan asc
				')
			);
			
		$grafik1 = DB::select(
				DB::raw('
					select distinct month(date) as bulan, year(date) as tahun, max(content) as content
					from t_dashboard_sosmed
					where id_sosmed = 4
					group by bulan, tahun
					order by tahun asc, bulan asc
				')
			);
			
		$grafik2 = DB::select(
				DB::raw('
					select distinct month(date) as bulan, year(date) as tahun, max(likes) as suka
					from t_dashboard_sosmed
					where id_sosmed = 4
					group by bulan, tahun
					order by tahun asc, bulan asc
				')
			);

		for($c=0; $c<count($grafik0); $c++){
			
			if($c==0){
				
				$follower[$c] = 0;
				$content[$c] = 0;
				$like[$c] = 0;
				
			} else {
				
				$follower[$c] = $grafik0[$c]->follower - $grafik0[($c-1)]->follower;
				$content[$c] = $grafik1[$c]->content - $grafik1[($c-1)]->content;
				$like[$c] = $grafik2[$c]->suka - $grafik2[($c-1)]->suka;
			}
			
			$value[ $grafik0[$c]->bulan ] = $follower[$c];
			$value1[ $grafik1[$c]->bulan ] = $content[$c];
			$value2[ $grafik2[$c]->bulan ] = $like[$c];
			
		}
		
		$dataKat = []; $dataKat1 = []; $dataKat2 = [];
		for($d=1; $d<13; $d++){

			$valueBar = isset($value[$d]) ? $value[$d]:0;
			array_push($dataKat, $valueBar);
			
			$valueBar1 = isset($value1[$d]) ? $value1[$d]:0;
			array_push($dataKat1, $valueBar1);
			
			$valueBar2 = isset($value2[$d]) ? $value2[$d]:0;
			array_push($dataKat2, $valueBar2);
			
		}		
		
		return response()->json([						
			'data0' => $dataKat,
			'data1' => $dataKat1,
			'data2' => $dataKat2,
			'follower' => $value[ $grafik0[(count($grafik0)-1)]->bulan ],	
			'content' => $value1[ $grafik1[(count($grafik1)-1)]->bulan ],			
			'like' => $value2[ $grafik2[(count($grafik2)-1)]->bulan ],			
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

    public function upload(Request $request)
    {
        if($request->ajax()){            
				
            DB::beginTransaction();
            try {
				if($request->file('file')){
					
					// membuat nama file unik
					$ext = $request->file('file')->getClientOriginalExtension();
					$nama_file = date("ymdHis").'.'.$ext;
					$nama_file_ori = $request->file('file')->getClientOriginalName();			
					$path = "siantok/video/";	
					$size = $request->file('file')->getSize();									
					$sizex = $size-1;									
										
					$request->file('file')-> move($path, $nama_file);
					$insert = SosmedContentFile::create([						
						"id_sosmed"=> $request->idSosmed,				
						"sosmed_content_when"=> date("Y-m-d H:i:s"),
						"sosmed_content_who"=> Session::get('name'),													
						"sosmed_content_name"=> $nama_file,
						"sosmed_content_name_ori"=> $nama_file_ori,
						"sosmed_content_path"=> $path,
						"sosmed_content_exe"=> $ext,
						"sosmed_content_desc"=> $request->desc,
						"open_id"=> base64_encode($request->openId),						
					]);
						
					/* 1. Get Url Video ke Tiktok */	
					$headers = [	
						'Authorization: Bearer '.Session::get('accessToken'),		
						'Content-Type: application/json; charset=utf-8',						
					];				

					$payload['source_info'] = ([
						'source' => 'FILE_UPLOAD',
						'video_size' => '1539811',
						'chunk_size' => '1539811',
						'total_chunk_count' => 1
					]);
					

					$url = "https://open.tiktokapis.com/v2/post/publish/inbox/video/init/";					

					$client = curl_init();
					curl_setopt($client, CURLOPT_URL, $url);
					curl_setopt($client, CURLOPT_HTTPHEADER, $headers); 				
					curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($client, CURLOPT_POSTFIELDS, json_encode($payload));					
					
					$response = curl_exec($client);
					$result = json_decode($response);


var_dump(json_encode($payload));
var_dump($result);


//					curl_close( $client );
					
					/* 2. Upload Video ke Tiktok sebagai draft */	
/* 					$headers1= [							
						'Content-Range: bytes 0-1539810/1539811',
						'Content-Length: 1539811',						
						'Content-Type: video/mp4',						
					];				 */
					
					//$payload1 = '@/path/to/file/'.$nama_file;
//					$payload1 = 'https://siricing.perbanas.ac.id/siantok/video/'.$nama_file;
					//$payload1 = ['@/'.$path.$nama_file];
					//$payload1 = ['data: @/'.$path.$nama_file];
					//$payload1 = ['data' => '@/'.$path.$nama_file];	
										
													
//					$url1 = $result->data->upload_url;		
					
					/* echo '<br>'.$url1.'-'.$nama_file.'<br>';	 				
					echo '<br>'.$size.'-'.$payload1.'<br>';	 	 */			
					
//					$client1 = curl_init();
										
					/* curl_setopt($client1, CURLOPT_URL, $url1);					
					curl_setopt($client1, CURLOPT_HTTPHEADER, $headers1); 	
					curl_setopt($client1, CURLOPT_VERBOSE, true);						
					curl_setopt($client1, CURLOPT_RETURNTRANSFER, 1);					
					curl_setopt($client1, CURLOPT_POSTFIELDS, ($payload1));
					curl_setopt($client1, CURLOPT_CUSTOMREQUEST, "PUT"); */
										
//					$out_file = "@/path/to/file";
					//$fp = fopen($out_file, "w");
					
/* 					curl_setopt($client1, CURLOPT_URL, $url1);
					curl_setopt($client1, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($client1, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($client1, CURLOPT_CUSTOMREQUEST, "PUT");					
					curl_setopt($client1, CURLOPT_FILE, $out_file);										

					$response1 = curl_exec($client1);
					$result1 = json_decode($response1);
 */					
					/* var_dump($response1); */
//					var_dump($result1); echo "<br><br>";
					
//					var_dump(curl_getinfo($client1)) . '<br/>';
					/* echo curl_errno($client1) . '<br/>';
					echo curl_error($client1) . '<br/>'; */
					
					/* if($insert) {
						DB::commit();             
						return response()->json(['status'=>'insert_successful','msg'=>'Data Berhasil Ditambahkan']);                    
					} else {
						return response()->json(['status'=>'insert_failed','msg'=>'Data Gagal Ditambahkan']);
					} */
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
