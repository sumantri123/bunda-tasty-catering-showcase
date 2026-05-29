<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\NilaiTukar;
use App\Models\NasabahIndividu;
use App\Models\TRekeningNasabah;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use App\Models\File;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use DB;
use Session;
use Auth;

class DataAwalImport implements  ToCollection, WithHeadingRow
{
    private $nama_file; 
    private $path; 

    
    public function __construct($nama_file,$path)
    {
        $this->nama_file = $nama_file; 
        $this->path = $path; 
    }
    
    public function collection(Collection  $rows)
    {   

        $kolom_format = array(                   
            'kode_nasabah',
            'status_nasabah',
            'sa_giro_temp',
            'sa_tab_temp',
            'sa_dep_temp',
            'sa_pin_temp',
            'sa_pinkre_temp',
            'sa_pin_temp_2',
            'jangka_waktu_temp',
            'irr_temp',
            'suku_bunga',
            'provisi',
            'nama',
            'kewarganegaraan',
            'kota_ktp'            
        );
        
        $kolom_excel = $rows[0]->toArray();
        $error = false;
        foreach($kolom_format as $kolom){
            if(array_key_exists($kolom,$kolom_excel)){
                
            } else {
                $error = true;
            }
        } 

        if($error) {                    
            $this->hasil = ["status"=>404,"message"=>"Format FIle Excel Tidak Sesuai Template"];
            
        }else {     
            
            DB::beginTransaction();
            try {

                // Insert File
                $insertDataFile = File::create([
                    "file_name"=> $this->nama_file,
                    "file_path"=> $this->path,                        
                    "id_kelas"=> Session::get('kelas'),                        
                    "user_record"=> Auth::user()->name,   
                    "dt_record"=> date("Y-m-d H:i:s"),
                ]);

                // Insert Nilai Tukar (TT, BN, TC)
                $LNilaiTukar = NilaiTukar::where('id_kelas','=',Session::get('kelas'))->get();
                if(count($LNilaiTukar)==0) {
                   
                    $kursNama = array("Kurs TT","Kurs BN","Kurs TC");
                    $kursBeli = array(9400,10400,8400);
                    $kursJual = array(9500,10500,8500);

                    for($a=0; $a<3; $a++){
                        $insertNilaiTukar = NilaiTukar::create([
                            "kurs_nama"=> $kursNama[$a],
                            "kurs_beli"=> $kursBeli[$a],
                            "kurs_jual"=> $kursJual[$a],                       
                            "id_kelas"=> Session::get('kelas'),                       
                            "created_at"=> date("Y-m-d H:i:s")
                        ]);
                    }                    
                }

                foreach ($rows as $row) {

                    $cekData = NasabahIndividu::where([
                        ['nama','=',$row['nama']],                                    
                        ['id_kelas','=',Session::get('kelas')],
                    ])->count();
                    
                    if($cekData==0){
                        // Insert Data Cif
                        $orderObj = DB::table('m_nasabah')->select('cif')->latest('id')->first();        
                        if ($orderObj) {
                            $lastKodeCif = explode('.',$orderObj->cif);
                            $lastCif2 = $lastKodeCif[1];
                            //$removed1char = substr($lastCif2, 1);
            
                            if($lastKodeCif[2]!=date('Y')){
                                $cif_2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                            } else {
                                $cif_2 = str_pad($lastCif2 + 1, 5, "0", STR_PAD_LEFT);
                            }
                            
                        } else {
                            $cif_2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                        }
            
                        $cifAll = $row['kode_nasabah'].'.'.$cif_2.'.'.date('Y');
                        
                        //echo $row['jangka_waktu_temp'].'-'.$row['irr_temp'].'-'.$row['suku_bunga'].'-'.$row['provisi'].'-'.$row['status_nasabah'].'-'.$row['kota_ktp'];
                        $insertDataCif = NasabahIndividu::create([
                            "cif"=> $cifAll,
                            "sa_giro_temp"=> $row['sa_giro_temp'],
                            "sa_tab_temp"=> $row['sa_tab_temp'],
                            "sa_dep_temp"=> $row['sa_dep_temp'],
                            "sa_pin_temp"=> $row['sa_pin_temp'],
                            "sa_pinkre_temp"=> $row['sa_pinkre_temp'],
                            "sa_pin_temp_2"=> $row['sa_pin_temp_2'],
                            "jangka_waktu_temp"=> $row['jangka_waktu_temp'],
                            "irr_temp"=> $row['irr_temp'],
                            "suku_bunga"=> $row['suku_bunga'],
                            "provisi"=> $row['provisi'],
                            "nama"=> $row['nama'],
                            "status_nasabah"=> $row['status_nasabah'],
                            "kewarganegaraan"=> $row['kewarganegaraan'],
                            "kota_ktp"=> $row['kota_ktp'],                        
                            "id_kelas"=> Session::get('kelas'),                       
                            "id_file"=> $insertDataFile->file_id,                       
                            "dt_record"=> date("Y-m-d H:i:s"),
                            "created_at"=> Auth::user()->name,   
                        ]);                        
                    } else {
                        $this->hasil = ["500"=>200,"msg"=>"Data Nasabah Sudah Pernah Diupload"];
                        //return response()->json(['status'=>'insert_failed2','msg'=>'Data Nasabah Sudah Pernah Diupload']);
                    }
                }

                DB::commit();
                $this->hasil = ["status"=>200,"msg"=>"Data Berhasil Masuk"];    

            } catch (\Throwable $e) {                
                DB::rollback();
                throw $e;                                     
                $this->hasil = ["500"=>200,"msg"=>"Terjadi Kesalahan Pada Sistem"];

            }                
        }
    }
      
    public function getHasil()
    {
        return $this->hasil;
    }
}
