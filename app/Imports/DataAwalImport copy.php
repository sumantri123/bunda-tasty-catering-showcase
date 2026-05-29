<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\NilaiTukar;
use App\Models\NasabahIndividu;
use App\Models\TRekeningNasabah;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use DB;
use Session;
use Auth;

class DataAwalImportCopy implements  ToCollection, WithHeadingRow
{
    
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

            //Insert Nilai Tukar (TT, BN, TC)
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
                            "dt_record"=> date("Y-m-d H:i:s"),
                            "created_at"=> Auth::user()->name,   
                        ]);
                    }                    
                }

                foreach ($rows as $row) {
                    
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
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "created_at"=> Auth::user()->name,   
                    ]);    
                    
            // Insert rekening tabungan
                    $orderObj0 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->latest('id')->first();        
                    if ($orderObj0) {
                        $lastKodeNumber = explode('.',$orderObj0->nomor_rekening);
                        $lastKodeNumber2 = $lastKodeNumber[1];
                        $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 4, "0", STR_PAD_LEFT);
                        
                    } else {
                        $KodeNumber2 = str_pad(1, 4, "0", STR_PAD_LEFT);                 
                    }
                    
                    $kodePerkiraan = "302001";
                    $nomorRekening = $kodePerkiraan.'.'.$KodeNumber2;
                    $jenisRekening = 2;
                    
                    $insertRekta = TRekeningNasabah::create([
                        "nomor_rekening"=> $nomorRekening,
                        "id_perkiraan"=> 74,				
                        "id_jenis_rekening"=> $jenisRekening,
                        "id_nasabah"=> $insertDataCif->id,
                        "tanggal_buka"=> date('Y-m-d'), 
                        "id_kelas"=> Session::get('kelas'),
                        "user_record"=> Auth::user()->name,                              
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
                        "user_record"=> Auth::user()->name                 
                    ]);
                    

                    // Insert Jurnal Bagian Detail
                    $insertJBDet = JurnalBagianDetail::create([
                        "id_perkiraan"=> 74,
                        "id_jurnal_bagian"=> $insertJB1->jurnal_bagian_id,
                        "id_jenis_transaksi"=> 2,
                        "jurnal_det_nominal"=> $row['sa_tab_temp'],
                        "id_rekening"=> $insertRekta->id,
                        "id_kode_transaksi"=> 7,
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Auth::user()->name,   
                    ]);


            // Insert Rekening Giro
                    $orderObj1 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->latest('id')->first();        
                    if ($orderObj1) {
                        $lastKodeNumber = explode('.',$orderObj1->nomor_rekening);
                        $lastKodeNumber2 = $lastKodeNumber[1];
                        $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 4, "0", STR_PAD_LEFT);
                        
                    } else {
                        $KodeNumber2 = str_pad(1, 4, "0", STR_PAD_LEFT);                 
                    }
                    
                    $kodePerkiraan = "301001";
                    $nomorRekening = $kodePerkiraan.'.'.$KodeNumber2;
                    $jenisRekening = 1; 

                    $insertRekta1 = TRekeningNasabah::create([
                        "nomor_rekening"=> $nomorRekening,
                        "id_perkiraan"=> 71,				
                        "id_jenis_rekening"=> $jenisRekening,
                        "id_nasabah"=> $insertDataCif->id,
                        "tanggal_buka"=> date('Y-m-d'), 
                        "id_kelas"=> Session::get('kelas'),
                        "user_record"=> Auth::user()->name,                              
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
                        "user_record"=> Auth::user()->name                 
                    ]);
                    

                    // Insert Jurnal Bagian Detail
                    $insertJBDet1 = JurnalBagianDetail::create([
                        "id_perkiraan"=> 71,
                        "id_jurnal_bagian"=> $insertJB1->jurnal_bagian_id,
                        "id_jenis_transaksi"=> 2,
                        "jurnal_det_nominal"=> $row['sa_giro_temp'],
                        "id_rekening"=> $insertRekta1->id,                    
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Auth::user()->name,   
                    ]);
                }

            // Insert Rekening Deposito 1 Bulan
                    $orderObj2 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->latest('id')->first();
                    if ($orderObj2) {
                        $lastKodeNumber = explode('.',$orderObj2->nomor_rekening);
                        $lastKodeNumber2 = $lastKodeNumber[1];
                        $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 4, "0", STR_PAD_LEFT);
                        
                    } else {
                        $KodeNumber2 = str_pad(1, 4, "0", STR_PAD_LEFT);                 
                    }
                    
                    $kodePerkiraan = "303001";
                    $nomorRekening = $kodePerkiraan.'.'.$KodeNumber2;
                    $jenisRekening = 3;
                    
                    // Insert Rekening Tabungan
                    $insertRekta2 = TRekeningNasabah::create([
                        "nomor_rekening"=> $nomorRekening,
                        "id_perkiraan"=> 76,
                        "id_jenis_rekening"=> $jenisRekening,
                        "id_nasabah"=> $insertDataCif->id,
                        "tanggal_buka"=> date('Y-m-d'), 
                        "id_kelas"=> Session::get('kelas'),
                        "user_record"=> Auth::user()->name,                              
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
                        "user_record"=> Auth::user()->name                 
                    ]);
                    

                    // Insert Jurnal Bagian Detail
                    $insertJBDet2 = JurnalBagianDetail::create([
                        "id_perkiraan"=> 76,
                        "id_jurnal_bagian"=> $insertJB2->jurnal_bagian_id,
                        "id_jenis_transaksi"=> 2,
                        "jurnal_det_nominal"=> $row['sa_dep_temp'],
                        "id_rekening"=> $insertRekta2->id,                    
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Auth::user()->name,   
                    ]);


            // Insert Rekening Pinjaman
                    $kodePerkiraanReguler = "106003"; // pinjaman reguler
                    $kodePerkiraanInstallment = "106004"; // pinjaman installment
                    $kodePerkiraanCC = "106005"; // pinjaman kartu kredit
                    $jenisRekening = 4;            
                    $idPerkiraan = array("27","28","28","29");
                    $idPinjaman = array("2","1","1","3");
                    $kode = array($kodePerkiraanReguler,$kodePerkiraanInstallment,$kodePerkiraanInstallment,$kodePerkiraanCC);
                    $bagian = "CS";            

                    for($b=0; $b<4; $b++){

                        $saPinTem[$a] = $row['sa_pin_temp'];
                        $saPinTem2[$a] = $row['sa_pin_temp_2']; 
                        $saPinCC[$a] = $row['sa_pinkre_temp'];
                        $saldoAwalTemx = array($row['sa_pin_temp'],$row['sa_pin_temp'],$row['sa_pin_temp_2'],$row['sa_pinkre_temp']);
                        $saldoAwalTemy = array($row['sa_pin_temp'],$row['sa_pin_temp_2']);

                        //untuk 106004 default rekening dibuatkan 2x (untuk pinjaman motor dan kpr)
                        $loopingInstallment = ($kode[$b] == "106004") ? 2:1;                    

                        for($e=0, $f=1; $e<$loopingInstallment; $e++, $f++){

                            $orderObj3 = DB::table('t_rekening_nasabah')->select('nomor_rekening')->latest('id')->first();
                            if ($orderObj3) {
                                $lastKodeNumber[$b][$e] = explode('.',$orderObj3->nomor_rekening);
                                $lastKodeNumber2[$b][$e] = substr($lastKodeNumber[$b][$e][1],0,4);
                                $generate[$b][$e] = ($lastKodeNumber2[$b][$e]);
                                $KodeNumber2[$b][$e] = str_pad(($generate[$b][$e] + 1), 4, "0", STR_PAD_LEFT);
                                
                            } else {
                                $KodeNumber2[$b][$e] = str_pad(1, 4, "0", STR_PAD_LEFT);
                            }

                            $nomorRekeningReguler[$b][$e] = $kode[$b].'.'.$KodeNumber2[$b][$e].$f;

                            $insertRekPin[$b][$e] = TRekeningNasabah::create([
                                "nomor_rekening"=> $nomorRekeningReguler[$b][$e],
                                "id_perkiraan"=> $idPerkiraan[$b],				
                                "id_jenis_rekening"=> $jenisRekening,
                                "id_pinjaman"=> $idPinjaman[$b],				
                                "id_nasabah"=> $insertDataCif->id,
                                "tanggal_buka"=> date('Y-m-d'), 
                                "id_kelas"=> Session::get('kelas'),
                                "user_record"=> Auth::user()->name,                              
                                "dt_record"=> date("Y-m-d H:i:s")   
                            ]);

                            //insert rekening pinjaman
                            if($kode[$b] != "106005") {

                                $saldoAwalTem[$b][$e] = ($kode[$b] == "106004") ? $saldoAwalTemy[$e]:$saldoAwalTemx[$b];
                                $provisiNominal = ((($row['provisi'])/100) * $saldoAwalTem[$b][$e]);                                                        
                                $angsuranPerbulan[$b][$e] = ($saldoAwalTem[$b][$e] / $row['jangka_waktu_temp']);
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
                                $idPerkiraanProvisi[$b][$e] = ($kode[$b]=="106003") ? 193 : 194;
                                $idPerkiraanDropping[$b][$e] = ($kode[$b]=="106003") ? 27 : 28;
                                $jenisPinjaman[$b][$e] = ($kode[$b]=="106003") ? 2 : 1; // 1: installment; 2:reguler

                                // Insert di Tabel Pinjaman
                                $insertPinjaman[$b][$e] = TRekeningPinjaman::create([
                                    "jenis_pinjaman"=> $jenisPinjaman[$b][$e],  
                                    "id_rekening"=> $insertRekPin[$b][$e]->id,
                                    "tanggal_realisasi"=> date("Y-m-d H:i:s"),
                                    "jangka_waktu"=> $row['jangka_waktu_temp'],
                                    "nominal_pokok"=> $saldoAwalTem[$b][$e],
                                    "bunga_efektif_anuitas"=> $row['suku_bunga'], 
                                    "bunga_efektif_bulan"=> $row['irr_temp'], 
                                    "provisi_persen"=> $row['provisi'], 
                                    "provisi_nominal"=> $provisiNominal,
                                    "angsuran_bulan"=> $angsuranPerbulan[$b][$e], 
                                    "id_perkiraan_provisi"=> $idPerkiraanProvisi[$b][$e],
                                    "bukti_provisi"=> $buktiProvisi[$b][$e],
                                    "id_perkiraan_dropping"=> $idPerkiraan[$b][$e],
                                    "bukti_dropping"=> $buktiDropping[$b][$e],
                                    "keterangan"=> "Data Saldo Awal",
                                    "id_kelas"=> Session::get('kelas'),                        
                                    "dt_record"=> date("Y-m-d H:i:s"),
                                    "user_record"=> Auth::user()->name                 
                                ]); 

                                $jangkaWaktu = ($cekData[$a]->jangka_waktu_temp)+1;
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
                                    $sukuBunga = ($row['suku_bunga'])/100;
                                    $provisiBunga = ($row['provisi'])/100;  
                                    $IRRBunga = ($row['irr_temp'])/100;  
                                    $tagihanBunga = $nominalPokok*($sukuBunga/12);                            
                                    $provisiNominal = $provisiBunga * $nominalPokok;                            
                                    $estimasiAwal = $provisiNominal - $nominalPokok;                                                            
                                    $saldoAkhirAwal = $nominalPokok - $provisiNominal;                            
                                    
                                    if($c==0){
                                        $saldoAwalx = 0;
                                    } else if($c==1) {
                                        $saldoAwalx = $saldoAkhirAwal;
                                    } else {
                                        $saldoAwalx = $saldoAkhir[$c-1];
                                    }
    
                                    $IRRNominal = $saldoAwalx * $IRRBunga;
                                    $amortisasi = $IRRNominal - $tagihanBunga;
    
                                    if(($jenisPinjaman[$b][$e])==1){                                                                
                                        $angsuranPokok = $nominalPokok/$row['jangka_waktu_temp'];
                                    } else {
                                        $angsuranPokok = (($row['jangka_waktu_temp'])==$KodeNumberAngs2) ? $nominalPokok:0;                                                               
                                    }             
    
                                    $estimasi = $angsuranPokok + $tagihanBunga;
                                    $saldoAkhir[$c] = $saldoAwalx + $IRRNominal - $angsuranPokok - $tagihanBunga;
                                    
                                    $insertAngsuran = TRekeningAngsuranPinjaman::create([
                                        "id_rekening_pinjaman"=> $insertPinjaman[$b][$e]->rekening_pinjaman_id,
                                        "id_rekening"=> $insertRekPin[$b][$e]->id,  
                                        "angsuran_ke"=> $KodeNumberAngs2,
                                        "tanggal_jth_tempo"=> ($c==0) ? $date:$nextDate,
                                        "estimasi"=> ($c==0) ? $estimasiAwal:$estimasi,
                                        "saldo_awal"=> $saldoAwalx,
                                        "suku_bunga_efektif"=> ($c==0) ? 0:$IRRNominal,
                                        "angsuran_pokok"=> ($c==0) ? 0:$angsuranPokok,
                                        "tagihan_bunga"=> ($c==0) ? 0:$tagihanBunga,
                                        "amortisasi"=> ($c==0) ? 0:$amortisasi,
                                        "saldo_akhir"=> ($c==0) ? $saldoAkhirAwal:$saldoAkhir[$c],
                                        "dt_record"=> date("Y-m-d H:i:s"),
                                        "user_record"=> Auth::user()->name                 
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
                                        "user_record"=> Auth::user()->name                 
                                    ]);
    
                                    //Insert Jurnal Bagian Detail (Pinjaman)
                                    $insertJBDet1 = JurnalBagianDetail::create([
                                        "id_perkiraan"=> $idPerkiraanJBDet[$d],
                                        "id_jurnal_bagian"=> $insertJB[$d]->jurnal_bagian_id,
                                        "id_jenis_transaksi"=> $idJenisTransaksi[$d],
                                        "jurnal_det_nominal"=> $nominal[$d],
                                        "id_rekening"=> $insertRekPin[$b][$e]->id,
                                        "dt_record"=> date("Y-m-d H:i:s"),
                                        "user_record"=> Auth::user()->name,   
                                    ]);
                                    
                                }

                            }
                        }

                    }

                DB::commit();
                $this->hasil = ["status"=>200,"message"=>"Data Berhasil Masuk"];    

            } catch (\Throwable $e) {                
                DB::rollback();
                throw $e;                                     
                $this->hasil = ["500"=>200,"message"=>"Terjadi Kesalahan Pada Sistem"];

            }                
        }
    }
      
    public function getHasil()
    {
        return $this->hasil;
    }
}
