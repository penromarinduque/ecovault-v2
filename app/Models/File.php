<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'date_released' => 'date'
    ];

    public function getIconAttribute()
    {
        if($this->file_type == "pdf"){
            return "fas fa-file-pdf";
        }
        if(in_array($this->file_type, ["jpeg", "png", "jpg", "gif", "svg", "bmp", "tiff", "tif"])){
            return "fas fa-file-image";
        }
        return "fas fa-file";
    }
}
