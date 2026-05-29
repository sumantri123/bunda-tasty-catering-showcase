<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Role;
use App\Models\Modul;
use Auth;

class RoleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $modul)
    {	
        $role = Role::findOrFail(auth()->user()->id_role);
        if($role){            
            foreach ($role->moduls as $RoleModul) {
                if($RoleModul->modul->nama_modul == $modul){ //echo $RoleModul->modul->nama_modul.' - '.$modul;
                    return $next($request);
                }
            }  
        }
        
        
        return redirect()->route('admin.index');
    }
}
