<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\NasabahIndividu;
use App\Models\TRekeningNasabah;
use App\Models\TRekeningPinjaman;
use App\Models\TRekeningAngsuranPinjaman;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use App\Imports\DataAwalImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Auth;
use Session;
use File;

class DataAwalController extends Controller
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
            'title' => 'Upload Data Awal',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'classTable' => 'table table-sm table-striped',            
        );        
        $returnHTML = view('data_awal/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        //return view('data_awal/index', compact('data'));
    }       

    public function getData()
    {
        $dataFile = DB::select(                
            
            DB::raw("
                    select *
                    from m_saldo_awal_file a
                    left join (
                        select count(id) as total_data, id_file                             
                        from m_nasabah b  
                        where b.id_kelas = ".Session::get('kelas')."
                        group by id_file                           
                    ) as c on a.file_id = c.id_file
                    where a.id_kelas = ".Session::get('kelas')."
                ")
        );  

        if($dataFile) {
            return response()->json([
                'status'=>'oke',
                'data' => $dataFile
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
    }

    public function CreateRekTab(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = NasabahIndividu::where([
                ['id_file','=',$id],            
                ['id_kelas','=',Session::get('kelas')],
            ])->get();

            for($a=0; $a<count($data); $a++){

                $orderObj0 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->where('id_perkiraan','=',Session::get('3XXTABUH_ID'))->where('id_kelas','=',Session::get('kelas'))->latest('id')->first();        
                if ($orderObj0) {
                    $lastKodeNumber = explode('.',$orderObj0->nomor_rekening);
                    $lastKodeNumber2 = $lastKodeNumber[1];
                    $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 4, "0", STR_PAD_LEFT);
                    
                } else {
                    $KodeNumber2 = str_pad(1, 4, "0", STR_PAD_LEFT);                 
                }
                
                $kodePerkiraan = Session::get('3XXTABUH');
                $nomorRekening = $kodePerkiraan.'.'.$KodeNumber2;
                $jenisRekening = 2;
                
                $cekData = TRekeningNasabah::where([
                    ['id_jenis_rekening','=',2],            
                    ['id_nasabah','=',$data[$a]->id],            
                    ['id_kelas','=',Session::get('kelas')],
                ])->count();

                if($cekData==0){

                    $insertRekta = TRekeningNasabah::create([
                        "nomor_rekening"=> $nomorRekening,
                        "id_perkiraan"=> Session::get('3XXTABUH_ID'),				
                        "id_jenis_rekening"=> $jenisRekening,
                        "id_nasabah"=> $data[$a]->id,
                        "tanggal_buka"=> date('Y-m-d'), 
                        "id_kelas"=> Session::get('kelas'),
                        "user_record"=> Session::get('login_as'),                              
                        "dt_record"=> date("Y-m-d H:i:s")   
                    ]);
    
                    // Insert Jurnal Bagian
                    $orderJB = DB::table('t_jurnal_bagian')->select('jurnal_no')->where('jurnal_keterangan','=','Data Saldo Awal')->latest('jurnal_bagian_id')->first();        
                    if ($orderJB) {
                        $lastKodeNumber = explode('.',$orderJB->jurnal_no);
                        $lastKodeNumber2 = $lastKodeNumber[1];
                        $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 5, "0", STR_PAD_LEFT);
                        
                    } else {
                        $KodeNumber2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                    }
                    
                    $bagian = "CS";
                    $jurnalNo = $bagian.'.'.$KodeNumber2.'.'.date('d-m-y');
    
                    $insertJB1 = JurnalBagian::create([
                        "jurnal_no"=> $jurnalNo,
                        "jurnal_keterangan"=> "Data Saldo Awal",
                        "jurnal_tanggal"=> date('Y-m-d'),
                        "jurnal_bagian"=> $bagian,                    
                        "id_kelas"=> Session::get('kelas'),                        
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as')           
                    ]);
                    
    
                    // Insert Jurnal Bagian Detail
                    $insertJBDet = JurnalBagianDetail::create([
                        "id_perkiraan"=> Session::get('3XXTABUH_ID'),
                        "id_jurnal_bagian"=> $insertJB1->jurnal_bagian_id,
                        "id_jenis_transaksi"=> 2,
                        "jurnal_det_nominal"=> $data[$a]->sa_tab_temp,
                        "id_rekening"=> $insertRekta->id,
                        "id_kode_transaksi"=> 7,
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as'),   
                    ]);        
                } else {
                    return response()->json(['status'=>'insert_failed2','msg'=>'Data Sudah Pernah Disimpan']);                  
                }                
            }      
            
            if($insertJBDet) {
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
    }

    public function CreateRekGiro(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = NasabahIndividu::where([
                ['id_file','=',$id],            
                ['id_kelas','=',Session::get('kelas')],
            ])->get();

            for($a=0; $a<count($data); $a++){

                $orderObj1 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->where('id_perkiraan','=',Session::get('3XXGIRUP_ID'))->where('id_kelas','=',Session::get('kelas'))->latest('id')->first();        
                if ($orderObj1) {
                    $lastKodeNumber = explode('.',$orderObj1->nomor_rekening);
                    $lastKodeNumber2 = $lastKodeNumber[1];
                    $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 4, "0", STR_PAD_LEFT);
                    
                } else {
                    $KodeNumber2 = str_pad(1, 4, "0", STR_PAD_LEFT);                 
                }
                
                $kodePerkiraan = Session::get('3XXGIRUP');
                $nomorRekening = $kodePerkiraan.'.'.$KodeNumber2;
                $jenisRekening = 1;                 

                $cekData = TRekeningNasabah::where([
                    ['id_jenis_rekening','=',1],            
                    ['id_nasabah','=',$data[$a]->id],            
                    ['id_kelas','=',Session::get('kelas')],
                ])->count();

                if($cekData==0){
                    $insertRekta1 = TRekeningNasabah::create([
                        "nomor_rekening"=> $nomorRekening,
                        "id_perkiraan"=> Session::get('3XXGIRUP_ID'),				
                        "id_jenis_rekening"=> $jenisRekening,
                        "id_nasabah"=> $data[$a]->id,
                        "tanggal_buka"=> date('Y-m-d'), 
                        "id_kelas"=> Session::get('kelas'),
                        "user_record"=> Session::get('login_as'),                              
                        "dt_record"=> date("Y-m-d H:i:s")   
                    ]);

                    // Insert Jurnal Bagian
                    $orderJB1 = DB::table('t_jurnal_bagian')->select('jurnal_no')->where('jurnal_keterangan','=','Data Saldo Awal')->latest('jurnal_bagian_id')->first();        
                    
                    if ($orderJB1) {
                        $lastKodeNumber = explode('.',$orderJB1->jurnal_no);
                        $lastKodeNumber2 = $lastKodeNumber[1];
                        $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 5, "0", STR_PAD_LEFT);
                        
                    } else {
                        $KodeNumber2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                    }
                    
                    $bagian = "CS";
                    $jurnalNo1 = $bagian.'.'.$KodeNumber2.'.'.date('d-m-y');

                    $insertJB1 = JurnalBagian::create([
                        "jurnal_no"=> $jurnalNo1,
                        "jurnal_keterangan"=> "Data Saldo Awal",
                        "jurnal_tanggal"=> date('Y-m-d'),
                        "jurnal_bagian"=> $bagian,                    
                        "id_kelas"=> Session::get('kelas'),                        
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as')
                    ]);
                    

                    // Insert Jurnal Bagian Detail
                    $insertJBDet1 = JurnalBagianDetail::create([
                        "id_perkiraan"=> Session::get('3XXGIRUP_ID'),
                        "id_jurnal_bagian"=> $insertJB1->jurnal_bagian_id,
                        "id_jenis_transaksi"=> 2,
                        "jurnal_det_nominal"=> $data[$a]->sa_giro_temp,
                        "id_rekening"=> $insertRekta1->id,                    
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as'),   
                    ]);    
                } else {
                    return response()->json(['status'=>'insert_failed2','msg'=>'Data Sudah Pernah Disimpan']);                  
                }   
            }      
            
            if($insertJBDet1) {
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
    }

    public function CreateRekDep(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = NasabahIndividu::where([
                ['id_file','=',$id],            
                ['id_kelas','=',Session::get('kelas')],
            ])->get();

            for($a=0; $a<count($data); $a++){

                $orderObj2 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->where('id_perkiraan','=',Session::get('3XXDERU1_ID'))->where('id_kelas','=',Session::get('kelas'))->latest('id')->first();
                if ($orderObj2) {
                    $lastKodeNumber = explode('.',$orderObj2->nomor_rekening);
                    $lastKodeNumber2 = $lastKodeNumber[1];
                    $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 4, "0", STR_PAD_LEFT);
                    
                } else {
                    $KodeNumber2 = str_pad(1, 4, "0", STR_PAD_LEFT);                 
                }
                
                $kodePerkiraan = Session::get('3XXDERU1');
                $nomorRekening = $kodePerkiraan.'.'.$KodeNumber2;
                $jenisRekening = 3;
                
                $cekData = TRekeningNasabah::where([
                    ['id_jenis_rekening','=',3],            
                    ['id_nasabah','=',$data[$a]->id],            
                    ['id_kelas','=',Session::get('kelas')],
                ])->count();

                if($cekData==0){
                    // Insert Rekening Deposito
                    $insertRekta2 = TRekeningNasabah::create([
                        "nomor_rekening"=> $nomorRekening,
                        "id_perkiraan"=> Session::get('3XXDERU1_ID'),
                        "id_jenis_rekening"=> $jenisRekening,
                        "id_nasabah"=> $data[$a]->id,
                        "tanggal_buka"=> date('Y-m-d'), 
                        "id_kelas"=> Session::get('kelas'),
                        "user_record"=> Session::get('login_as'),                              
                        "dt_record"=> date("Y-m-d H:i:s")   
                    ]);

                    // Insert Jurnal Bagian
                    $orderJB2 = DB::table('t_jurnal_bagian')->select('jurnal_no')->where('jurnal_keterangan','=','Data Saldo Awal')->latest('jurnal_bagian_id')->first();        
                    
                    if ($orderJB2) {
                        $lastKodeNumber = explode('.',$orderJB2->jurnal_no);
                        $lastKodeNumber2 = $lastKodeNumber[1];
                        $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 5, "0", STR_PAD_LEFT);
                        
                    } else {
                        $KodeNumber2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                    }
                    
                    $bagian = "CS";
                    $jurnalNo2 = $bagian.'.'.$KodeNumber2.'.'.date('d-m-y', strtotime($request->tgl));

                    $insertJB2 = JurnalBagian::create([
                        "jurnal_no"=> $jurnalNo2,
                        "jurnal_keterangan"=> "Data Saldo Awal",
                        "jurnal_tanggal"=> date('Y-m-d'),
                        "jurnal_bagian"=> $bagian,                    
                        "id_kelas"=> Session::get('kelas'),                        
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as')           
                    ]);
                    

                    // Insert Jurnal Bagian Detail
                    $insertJBDet2 = JurnalBagianDetail::create([
                        "id_perkiraan"=> Session::get('3XXDERU1_ID'),
                        "id_jurnal_bagian"=> $insertJB2->jurnal_bagian_id,
                        "id_jenis_transaksi"=> 2,
                        "jurnal_det_nominal"=> $data[$a]->sa_dep_temp,
                        "id_rekening"=> $insertRekta2->id,                    
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as'),   
                    ]); 

                } else {
                    return response()->json(['status'=>'insert_failed2','msg'=>'Data Sudah Pernah Disimpan']);                  
                }
            }      
            
            if($insertJBDet2) {
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
    }

    public function CreateRekPin(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = NasabahIndividu::where([
                ['id_file','=',$id],            
                ['id_kelas','=',Session::get('kelas')],
            ])->get();

            $kodePerkiraanReguler = Session::get('1XXPIREG'); // pinjaman reguler
            $kodePerkiraanInstallment = Session::get('1XXPIINS'); // pinjaman installment
            $kodePerkiraanCC =  Session::get('1XXPIKAK'); // pinjaman kartu kredit
            $jenisRekening = 4;            
            $idPerkiraan = array(Session::get('1XXPIREG_ID'),Session::get('1XXPIINS_ID'),Session::get('1XXPIKAK_ID'));
            $idPinjaman = array("2","1","3");
            $kode = array($kodePerkiraanReguler,$kodePerkiraanInstallment,$kodePerkiraanCC);
            $bagian = "CS";    

            for($a=0; $a<count($data); $a++){

                for($b=0; $b<3; $b++){
                    
                    $saPinTem[$b] = $data[$a]->sa_pin_temp;
                    $saPinTem2[$b] = $data[$a]->sa_pin_temp_2; 
                    $saPinCC[$b] = $data[$a]->sa_pinkre_temp;
                    $saldoAwalTemx = array($data[$a]->sa_pin_temp,$data[$a]->sa_pin_temp,$data[$a]->sa_pin_temp_2,$data[$a]->sa_pinkre_temp);
                    $saldoAwalTemy = array($data[$a]->sa_pin_temp,$data[$a]->sa_pin_temp_2);

                    //untuk 106004 default rekening dibuatkan 2x (untuk pinjaman motor dan kpr)
                    $loopingInstallment = ($kode[$b] == Session::get('1XXPIINS')) ? 2:1; 
                    $batasRekening = ($kode[$b] == Session::get('1XXPIINS')) ? 1:0; 

                    $cekData = DB::select(
                        DB::raw('
                            SELECT a.*
                            FROM t_rekening_nasabah as a 
                            LEFT JOIN m_nasabah as b on a.id_nasabah = b.id                 
                            LEFT JOIN t_jurnal_bagian_detail c on c.id_rekening = a.id                            
                            LEFT JOIN t_jurnal_bagian as e on c.id_jurnal_bagian = e.jurnal_bagian_id
                            WHERE a.id_kelas = "'.Session::get('kelas').'"
                            and id_jenis_rekening = 4                            
                            and e.jurnal_keterangan = "Data Saldo Awal"
                            and a.id_pinjaman = "'.$idPinjaman[$b].'"
                            and a.id_nasabah = "'.$data[$a]->id.'"
                            and c.id_perkiraan = "'.$idPerkiraan[$b].'"                            
                        ')                        
                    );        
            
                    if(count($cekData)<=$batasRekening){

                        for($e=0, $f=1; $e<$loopingInstallment; $e++, $f++){

                            $orderObj3 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->where('id_perkiraan','=',$idPerkiraan[$b])->where('id_kelas','=',Session::get('kelas'))->latest('id')->first();
                            if ($orderObj3) {
                                $lastKodeNumber[$b][$e] = explode('.',$orderObj3->nomor_rekening);
                                $lastKodeNumber2[$b][$e] = substr($lastKodeNumber[$b][$e][1],0,4);
                                $generate[$b][$e] = ($lastKodeNumber2[$b][$e]);
                                $KodeNumber2[$b][$e] = str_pad(($generate[$b][$e] + 1), 4, "0", STR_PAD_LEFT);
                                
                            } else {
                                $KodeNumber2[$b][$e] = str_pad(1, 4, "0", STR_PAD_LEFT);
                            }

							$nomorRekeningReguler[$b][$e] = (($loopingInstallment==2) && ($kode[$b] == Session::get('1XXPIINS'))) ? $kode[$b].'.'.$KodeNumber2[$b][0].$f : $kode[$b].'.'.$KodeNumber2[$b][$e].$f;
                           // $nomorRekeningReguler[$b][$e] = $kode[$b].'.'.$KodeNumber2[$b][$e].$f;                                            
                            $insertRekPin[$b][$e] = TRekeningNasabah::create([
                                "nomor_rekening"=> $nomorRekeningReguler[$b][$e],
                                "id_perkiraan"=> $idPerkiraan[$b],				
                                "id_jenis_rekening"=> $jenisRekening,
                                "id_pinjaman"=> $idPinjaman[$b],				
                                "id_nasabah"=> $data[$a]->id,
                                "tanggal_buka"=> date('Y-m-d'), 
                                "id_kelas"=> Session::get('kelas'),
                                "user_record"=> Session::get('login_as'),                              
                                "dt_record"=> date("Y-m-d H:i:s")   
                            ]);

                            //insert rekening pinjaman							
                            if($kode[$b] != Session::get('1XXPIKAK')) {

                                $saldoAwalTem[$b][$e] = ($kode[$b] == Session::get('1XXPIINS')) ? $saldoAwalTemy[$e]:$saldoAwalTemx[$b];
                                $provisiNominal = ((($data[$a]->provisi)/100) * $saldoAwalTem[$b][$e]);                                                        
                                $angsuranPerbulan[$b][$e] = ($saldoAwalTem[$b][$e] / $data[$a]->jangka_waktu_temp);
                                $orderPin = DB::table('t_rekening_pinjaman')->select('bukti_dropping')->where('keterangan','=','Data Saldo Awal')->latest('rekening_pinjaman_id')->first();        

                                if ($orderPin) {
                                    $lastKodeNumberPin[$b][$e] = explode('.',$orderPin->bukti_dropping);
                                    $lastKodeNumberPin2[$b][$e] = $lastKodeNumberPin[$b][$e][1];
                                    $KodeNumberPin2[$b][$e] = str_pad($lastKodeNumberPin2[$b][$e] + 1, 5, "0", STR_PAD_LEFT);
                                    
                                } else {
                                    $KodeNumberPin2[$b][$e] = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                                }

                                $buktiDropping[$b][$e] = $bagian.'D.'.$KodeNumberPin2[$b][$e].'.'.date('d-m-y');
                                $buktiProvisi[$b][$e] = $bagian.'P.'.$KodeNumberPin2[$b][$e].'.'.date('d-m-y');
                                $idPerkiraanProvisi[$b][$e] = ($kode[$b]==Session::get('1XXPIREG')) ? Session::get('7XXPPREG_ID') : Session::get('7XXPPINS_ID');
                                $idPerkiraanDropping[$b][$e] = ($kode[$b]==Session::get('1XXPIREG')) ? Session::get('1XXPIREG_ID') : Session::get('1XXPIINS_ID');
                                $jenisPinjaman[$b][$e] = ($kode[$b]==Session::get('1XXPIREG')) ? 2 : 1; // 1: installment; 2:reguler

                                // Insert di Tabel Pinjaman
                                $insertPinjaman[$b][$e] = TRekeningPinjaman::create([
                                    "jenis_pinjaman"=> $jenisPinjaman[$b][$e],  
                                    "id_rekening"=> $insertRekPin[$b][$e]->id,
                                    "tanggal_realisasi"=> date("Y-m-d H:i:s"),
                                    "jangka_waktu"=> $data[$a]->jangka_waktu_temp,
                                    "nominal_pokok"=> $saldoAwalTem[$b][$e],
                                    "bunga_efektif_anuitas"=> $data[$a]->suku_bunga,
                                    "bunga_efektif_bulan"=> $data[$a]->irr_temp,
                                    "provisi_persen"=> $data[$a]->provisi,
                                    "provisi_nominal"=> $provisiNominal,
                                    "angsuran_bulan"=> $angsuranPerbulan[$b][$e], 
                                    "id_perkiraan_provisi"=> $idPerkiraanProvisi[$b][$e],
                                    "bukti_provisi"=> $buktiProvisi[$b][$e],
                                    "id_perkiraan_dropping"=> $idPerkiraan[$b],
                                    "bukti_dropping"=> $buktiDropping[$b][$e],
                                    "keterangan"=> "Data Saldo Awal",
                                    "id_kelas"=> Session::get('kelas'),                        
                                    "dt_record"=> date("Y-m-d H:i:s"),
                                    "user_record"=> Session::get('login_as')
                                ]); 

                                $jangkaWaktu = ($data[$a]->jangka_waktu_temp)+1;
                                for($c=0; $c<$jangkaWaktu; $c++){

                                    // generate angsuran ke
                                    $orderAngs = DB::table('t_rekening_pinjaman_angsuran')->select('angsuran_ke','tanggal_jth_tempo')->where('id_rekening_pinjaman','=',$insertPinjaman[$b][$e]->rekening_pinjaman_id)->latest('pinjaman_angsuran_id')->first();        
                                    if ($orderAngs) {
                                        $KodeNumberAngs2 = str_pad($orderAngs->angsuran_ke + 1, 3, "0", STR_PAD_LEFT);
                                        $date = $orderAngs->tanggal_jth_tempo;
                                        
                                    } else {
                                        $KodeNumberAngs2 = str_pad(0, 3, "0", STR_PAD_LEFT);                 
                                        $date = date("Y-m-d");
                                    }    

                                    //generate tgl jatuh tempo                            
                                    $currentMonth = date("m",strtotime($date));
                                    $nextMonth = date("m",strtotime($date."+1 month"));

                                    if($currentMonth==$nextMonth-1 && (date("j",strtotime($date)) != date("t",strtotime($date)))){
                                        $nextDate = date('Y-m-d',strtotime($date." +1 month"));
                                    }else{
                                        $nextDate = (date('d') > 28) ? date('Y-m-d', strtotime("last day of next month",strtotime($date))) : date('Y-m-d', strtotime("next month",strtotime($date)));
                                    }

                                    $nominalPokok = $saldoAwalTem[$b][$e];
                                    $sukuBunga = ($data[$a]->suku_bunga)/100;
                                    $provisiBunga = ($data[$a]->provisi)/100;  
                                    $IRRBunga = ($data[$a]->irr_temp)/100;  
                                    //$tagihanBunga = $nominalPokok*($sukuBunga/12);                            
                                    $provisiNominal = $provisiBunga * $nominalPokok;                            
                                    $estimasiAwal = $provisiNominal - $nominalPokok;                                                            
                                    $saldoAkhirAwal = $nominalPokok - $provisiNominal;  
                                    
                                    if($c==0){
                                        $saldoAwalx = 0;
										$totalAngsuranPokok = 0;
										$totalTagihanBunga = 0;
										$totalIRR = 0;
                                    } else if($c==1) {
                                        $saldoAwalx = $saldoAkhirAwal;
                                    } else {
                                        $saldoAwalx = $saldoAkhir[$c-1];
                                    }

                                   									
									if(($jenisPinjaman[$b][$e])==1){ // installment     
										if($c!=$data[$a]->jangka_waktu_temp){
											$angsuranPokok = ($c==0) ? 0: round($nominalPokok/$data[$a]->jangka_waktu_temp,0);
											$totalAngsuranPokok += $angsuranPokok; 
											$tagihanBunga = ($c==0) ? 0: round($nominalPokok*($sukuBunga/12),0);
											$totalTagihanBunga += $tagihanBunga; 
											$IRRNominal = ($c==0) ? 0: round(($saldoAwalx * $IRRBunga),0);
											$totalIRR += $IRRNominal; 
										} else {
											$angsuranPokok = ($c==0) ? 0: $nominalPokok - $totalAngsuranPokok;
											$tagihanBunga = ($c==0) ? 0: round($nominalPokok*($sukuBunga/12)*$data[$a]->jangka_waktu_temp,0) - $totalTagihanBunga;											
											$IRRNominal = ($c==0) ? 0: round(($provisiNominal + ($nominalPokok*($sukuBunga/12)*$data[$a]->jangka_waktu_temp)),0) - $totalIRR;
										}
									} else { // reguler
										if($c!=$data[$a]->jangka_waktu_temp){
											$IRRNominal = ($c==0) ? 0: round(($saldoAwalx * $IRRBunga),0);
											$totalIRR += $IRRNominal; 
										} else {
											$tex = ($provisiNominal + ($nominalPokok*($sukuBunga/12)*$data[$a]->jangka_waktu_temp));																			
											$IRRNominal = ($c==0) ? 0: round(($tex - $totalIRR),0);
										}
										$angsuranPokok = (($data[$a]->jangka_waktu_temp)==$KodeNumberAngs2) ? $nominalPokok:0;                                                               
										$tagihanBunga = ($c==0) ? 0: round($nominalPokok*($sukuBunga/12));      
									}             
									
									//echo "tes".$tagihanBunga;
									//$IRRNominal = $saldoAwalx * $IRRBunga;
                                    $amortisasi = $IRRNominal - $tagihanBunga;
                                    $estimasi = $angsuranPokok + $tagihanBunga;
                                    $saldoAkhir[$c] = $saldoAwalx + $IRRNominal - $angsuranPokok - $tagihanBunga;
                                    
                                    $insertAngsuran = TRekeningAngsuranPinjaman::create([
                                        "id_rekening_pinjaman"=> $insertPinjaman[$b][$e]->rekening_pinjaman_id,
                                        "id_rekening"=> $insertRekPin[$b][$e]->id,  
                                        "angsuran_ke"=> $KodeNumberAngs2,
                                        "tanggal_jth_tempo"=> ($c==0) ? $date:$nextDate,
                                        "estimasi"=> ($c==0) ? $estimasiAwal:$estimasi,
                                        "saldo_awal"=> $saldoAwalx,
                                        "suku_bunga_efektif"=> $IRRNominal,
                                        "angsuran_pokok"=> $angsuranPokok,
                                        "tagihan_bunga"=> $tagihanBunga,
                                        "amortisasi"=> ($c==0) ? 0:$amortisasi,
                                        "saldo_akhir"=> ($c==0) ? $saldoAkhirAwal:$saldoAkhir[$c],
                                        "dt_record"=> date("Y-m-d H:i:s"),
                                        "user_record"=> Session::get('login_as')
                                    ]); 
                                }

                                // Insert Jurnal Bagian
                                $jurnalNoCollect = [$buktiDropping[$b][$e],$buktiProvisi[$b][$e]];
                                $nominal = [$saldoAwalTem[$b][$e],$provisiNominal];
                                $idJenisTransaksi = [1,2];                        
                                $idPerkiraanJBDet = array($idPerkiraanDropping[$b][$e],$idPerkiraanProvisi[$b][$e]);                        

                                for($d=0; $d<2; $d++){

                                    $insertJB[$d] = JurnalBagian::create([
                                        "jurnal_no"=> $jurnalNoCollect[$d],
                                        "jurnal_keterangan"=> "Data Saldo Awal",
                                        "jurnal_tanggal"=> date('Y-m-d'),
                                        "jurnal_bagian"=> $bagian,                    
                                        "id_kelas"=> Session::get('kelas'),                        
                                        "dt_record"=> date("Y-m-d H:i:s"),
                                        "user_record"=> Session::get('login_as')
                                    ]);

                                    //Insert Jurnal Bagian Detail (Pinjaman)
                                    $insertJBDet1 = JurnalBagianDetail::create([
                                        "id_perkiraan"=> $idPerkiraanJBDet[$d],
                                        "id_jurnal_bagian"=> $insertJB[$d]->jurnal_bagian_id,
                                        "id_jenis_transaksi"=> $idJenisTransaksi[$d],
                                        "jurnal_det_nominal"=> $nominal[$d],
                                        "id_rekening"=> $insertRekPin[$b][$e]->id,
                                        "dt_record"=> date("Y-m-d H:i:s"),
                                        //"user_record"=> Auth::user()->name,   
										"user_record"=> Session::get('login_as')
                                    ]);
                                    
                                }

                            } else {

								$orderPin = DB::table('t_rekening_pinjaman')->select('bukti_dropping')->where('keterangan','=','Data Saldo Awal')->latest('rekening_pinjaman_id')->first();        

                                if ($orderPin) {
                                    $lastKodeNumberPin[$b][$e] = explode('.',$orderPin->bukti_dropping);
                                    $lastKodeNumberPin2[$b][$e] = $lastKodeNumberPin[$b][$e][1];
                                    $KodeNumberPin2[$b][$e] = str_pad($lastKodeNumberPin2[$b][$e] + 1, 5, "0", STR_PAD_LEFT);
                                    
                                } else {
                                    $KodeNumberPin2[$b][$e] = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                                }

								$saldoAwalTem[$b][$e] = $data[$a]->sa_pinkre_temp;
								$buktiDropping[$b][$e] = $bagian.'K.'.$KodeNumberPin2[$b][$e].'.'.date('d-m-y');
								$idPerkiraanJBDet = Session::get('1XXPIKAK_ID');								

								// Insert di Tabel Pinjaman kartu kredit
                                $insertPinjaman[$b][$e] = TRekeningPinjaman::create([
                                    "jenis_pinjaman"=> 3,  
                                    "id_rekening"=> $insertRekPin[$b][$e]->id,
                                    "tanggal_realisasi"=> date("Y-m-d H:i:s"),                                    
                                    "nominal_pokok"=> $saldoAwalTem[$b][$e],
									"bukti_dropping"=> $buktiDropping[$b][$e],                                  
                                    "keterangan"=> "Data Saldo Awal",
                                    "id_kelas"=> Session::get('kelas'),                        
                                    "dt_record"=> date("Y-m-d H:i:s"),
                                    "user_record"=> Session::get('login_as')
                                ]); 
								
								$insertJB1 = JurnalBagian::create([
									"jurnal_no"=> $buktiDropping[$b][$e],
									"jurnal_keterangan"=> "Data Saldo Awal",
									"jurnal_tanggal"=> date('Y-m-d'),
									"jurnal_bagian"=> $bagian,                    
									"id_kelas"=> Session::get('kelas'),                        
									"dt_record"=> date("Y-m-d H:i:s"),
									"user_record"=> Session::get('login_as')
								]);

								

								//Insert Jurnal Bagian Detail (Pinjaman)
								$insertJBDet1 = JurnalBagianDetail::create([
									"id_perkiraan"=> $idPerkiraanJBDet,
									"id_jurnal_bagian"=> $insertJB1->jurnal_bagian_id,
									"id_jenis_transaksi"=> 1,
									"jurnal_det_nominal"=> $data[$a]->sa_pinkre_temp,
									"id_rekening"=> $insertRekPin[$b][$e]->id,
									"dt_record"=> date("Y-m-d H:i:s"),
									//"user_record"=> Auth::user()->name,   
									"user_record"=> Session::get('login_as')
								]);
                                    
                                
							}                            
                        }                        

                    } else {
                        return response()->json(['status'=>'insert_failed2','msg'=>'Data Sudah Pernah Disimpan']);                  
                    }
                }                             
            }    
            
            if($insertJBDet1) {
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
    }

    public function importExcel(Request $request)
    {        
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');        
        
        // membuat nama file unik
        $nama_file = rand()."_".$file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
        $file->move('excel',$nama_file);  
        $path = public_path('excel/');              
        
        // import data
        $import = Excel::import(new DataAwalImport($nama_file,$path), public_path('excel/'.$nama_file));
		
    }    

    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
            //"kode_perkiraan" => "required|unique:m_perkiraan,kode_perkiraan".($id ? ",".$id.",id" : "" ),            
        ], $messages);
    }

}
