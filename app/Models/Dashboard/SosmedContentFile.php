<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;

class SosmedContentFile extends Model
{
    protected $table = 't_sosmed_content';

    protected $primaryKey = 'sosmed_content_id';

    public $timestamps = false;
    protected $fillable = [
        "sosmed_content_id",
        "id_sosmed",
        "sosmed_content_when",
        "sosmed_content_who",
		"sosmed_content_name",
        "sosmed_content_name_ori",
		"sosmed_content_path",
		"sosmed_content_exe",
		"sosmed_content_desc",
		"open_id",		
    ];
    
}

