<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Folder extends Model
{
    //
    protected $guarded = [];

    public function parentFolder(){
        return $this->belongsTo(Folder::class, 'parent_folder_id', 'id');
    }

    public function getBreadcrumbData(){
        $links = [];
        $parent = $this;
        while($parent){
            $links[] = $parent;
            $parent = $parent->parentFolder;
        }
        // Log::info($links);
        return array_reverse($links);
    }
}
