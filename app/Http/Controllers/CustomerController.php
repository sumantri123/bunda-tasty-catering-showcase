<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;
use DB;



class CustomerController extends Controller
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
			'title' => 'Customer',
            'subtitle' => Session::get('subtitle'),			
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
        );       
				
       $returnHTML = view('customer/index',compact('data'))->render();
       return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
        $cs = DB::select(
					DB::raw('
						SELECT *
						FROM m_customer as a 						
						LEFT JOIN 
							(
								select id_customer
								from t_penawaran                            								
								group by id_customer
							) c on a.customer_id = c.id_customer
						where a.id_kelas = "'.Session::get('kelas').'"                    
						ORDER BY customer_nama asc
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

	public function send(Request $request)
    {				

		$result = array();
		$wsdl = "http://192.168.0.95/send_email_pppm/index.php?wsdl";
		$client = new nusoap_client($wsdl, true);
		$err = $client->getError();
		$to = "sumantri@perbanas.ac.id";	

		if ($err) {
			echo '<h2>Constructor error</h2>' . $err;
			exit();
		}

		try {

			$link = "<a href='http://staff.sisfo.perbanas.ac.id'><b>(Click Here)</b></a>";
			
			$subject = "Plot Reviewer Penelitian"; 
			$message = "Anda di plot sebagai Reviewer untuk Penelitian atas nama <br><br>";			
			$message .= "Mohon dapat melakukan penilaian melalui Sisfo ".$link."<br>Terima kasih";			
			
			$result = $client->call('fetchSendEmail', array($subject,$message,$to));
			
		}catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	

		return response()->json(['status'=>'insert_successful']);
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
                $insert = Customer::create([
                    "customer_nama"=> $request->nama,
                    "customer_alamat"=> $request->alamat,								
					"id_kelas"=> Session::get('kelas'),
                    "user_record"=> Session::get('login_as'),
                    "customer_pejabat"=> $request->owner,
					"customer_telp"=> $request->telp,
                    // "user_record"=> Auth::user()->name,
                    "dt_record"=> date("Y-m-d H:i:s")
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
            // if ($this->validateRequest($request, $id)->fails()) {

            //     return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request, $id)->messages()
            //         ]);
            // }
			
			$update = Customer::find($id);
			$update->update([
				"customer_nama"=> $request->nama,
				"customer_alamat"=> $request->alamat,								
				"id_kelas"=> Session::get('kelas'),
				"user_modified"=> Session::get('login_as'),
				"customer_pejabat"=> $request->owner,
				"customer_telp"=> $request->telp,
				// "user_record"=> Auth::user()->name,
				"dt_modified"=> date("Y-m-d H:i:s")
			]);
			$update->save();

         
            if($update) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }

    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            $query = Customer::find($id)->delete();
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
