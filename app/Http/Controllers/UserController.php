<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Modul;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;

class KaryawanController extends Controller
{ 
    public function index()
    {
      
        $roles = Role::get(); 
        return view('karyawan.index', compact('roles'));
    }

    public function create()
    {

        $roles = Role::get(); 
        return view('karyawan.form', compact('roles'));
    }

    public function getData()
    { 
        $karyawan = Karyawan::join('users','m_karyawan.user_id','=','users.id')
        ->join('m_role','users.role_id','=','m_role.role_id')
        ->where('users.id','!=',auth()->user()->id)
        ->select("m_karyawan.*",'m_role.nama_role','users.email','users.username','users.password')->get();
        // return $karyawan;

        if($karyawan) {
            return response()->json([
                'status'=>'oke',
                'data' => $karyawan
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
            "username" => "required|min:4|unique:users,username".($id ? ",".$id.",id" : "" ),
            "email" => "required|min:4|unique:users,email".($id ? ",".$id.",id" : "" ),
        ], $messages);
    }

 
    public function store(Request $request)
    { 
        if($request->ajax()){
            if ($this->validateRequest($request)->fails()) {
                return response()->json([
                    'status'=>'failed',
                    'error' => $this->validateRequest($request)->messages()
                ]); 

            } 
     
            DB::beginTransaction();
            try
            {
                $insertUser = User::create([
                    'name'=> $request->nama,
                    'email'=> $request->email,
                    'username'=> $request->username,
                    'password'=> Hash::make($request->password),
                    'role_id'=> $request->role_id,
                ]); 

                

                $insertKaryawan = Karyawan::create([

                    'user_id'=> $insertUser->id,

                    'nama'=> $request->nama,

                    'email'=> $request->email,

                    'username'=> $request->username,

                    "alamat"=>$request->alamat, 

                    "jenis_identitas"=>$request->jenis_identitas,

                    "foto_identitas"=>$request->foto_identitas,

                    "asal_institusi"=>$request->asal_institusi,

                    "pekerjaan"=>$request->pekerjaan, 

                    "no_hp"=>$request->no_hp,

                    "dt_record" => date("Y-m-d H:i:s"),

                    "user_record"=> 1

                ]);

                

                if($request->hasFile('foto_identitas')) {

                    $uploadedFile = $request->file('foto_identitas');

                    $extension = '.'.$uploadedFile->getClientOriginalExtension();

                    $filename  ="/asset/karyawan/foto_identitas/".$insertKaryawan->karyawan_id."_".date("dmy-His").$extension;

                    $uploadedFile->move(base_path('public/asset/karyawan/foto_identitas'), $filename);       

                    $insertKaryawan->update(['foto_identitas'=>$filename]);              

                }



                DB::commit(); 

                return response()->json(['status'=>'successful']);

            }

            catch (Exception $e)

            {

                DB::rollBack();

                return response()->json(['status'=>'failed']);

            }



        } else {

            return redirect('/');

        }



    }   



    public function edit($id)

    {

        $roles = Role::get(); 

        $karyawan = Karyawan::with('user')->find($id); 

        // return $karyawan;

        return view('karyawan.form', compact('karyawan','roles'));

    }



    public function update(Request $request, $id)

    {

    //   return $request->all();

        if($request->ajax()){

            

            $karyawan = Karyawan::find($id);

            if ($this->validateRequest($request, $karyawan->user_id)->fails()) {

                return response()->json([

                    'status'=>'failed',

                    'error' => $this->validateRequest($request, $karyawan->user_id)->messages()

                ]); 

            } 

            

            DB::beginTransaction();

            try

            {

                $foto = $karyawan->foto_identitas;

                $user = User::find($karyawan->user_id)->update([

                    'name'=> $request->nama,

                    'email'=> $request->email,

                    'username'=> $request->username,

                    'password'=> Hash::make($request->password),

                    'role_id'=> $request->role_id,

                ]); 

                

                if($request->hasFile('foto_identitas')) {

                    $uploadedFile = $request->file('foto_identitas');

                    $extension = '.'.$uploadedFile->getClientOriginalExtension();

                    $foto  ="/asset/karyawan/foto_identitas/".$id."_".date("dmy-His").$extension;

                    $uploadedFile->move(base_path('public/asset/karyawan/foto_identitas'), $foto);     

                }

                $karyawan = $karyawan->update([ 

                    'nama'=> $request->nama,

                    'email'=> $request->email,

                    'username'=> $request->username,

                    "alamat"=>$request->alamat, 

                    "jenis_identitas"=>$request->jenis_identitas,

                    "foto_identitas"=>$request->foto_identitas,

                    "asal_institusi"=>$request->asal_institusi,

                    "pekerjaan"=>$request->pekerjaan, 

                    "no_hp"=>$request->no_hp,

                    'foto_identitas'=>$foto,

                    "dt_modified" => date("Y-m-d H:i:s"),

                    "user_modified"=> 1

                ]);

                

                DB::commit(); 

                return response()->json(['status'=>'successful']);

            }

            catch (Exception $e)

            {

                DB::rollBack();

                return response()->json(['status'=>'failed']);

            }



        } else {

            return redirect('/');

        }





    }

 

    public function deleteData(Request $request, $id)

    {



        if($request->ajax()){

            $query = Karyawan::find($id);

            $user =  User::find($query->user_id)->delete();

            $query->delete();  



            if($query) {

                return response()->json(['status'=>'delete_successful']);

            } else {

                return response()->json(['status'=>'delete_failed']);

            }

        } else {

            return redirect('/');

        }

    }

}

