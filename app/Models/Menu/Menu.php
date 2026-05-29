<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'm_menu';

    protected $primaryKey = 'menu_id';

    public $timestamps = false;
    protected $fillable = [
        "menu_id",
        "menu_nama",
        "menu_harga",
        "id_perkiraan",
		"id_lembaga",
        "menu_status",        
		"menu_who",        
		"menu_when",        
    ];
    
}


