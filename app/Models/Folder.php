<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Folder extends Model
{
    //
    protected $guarded = [];

    public function parentFolder(){
        return $this->belongsTo(Folder::class, 'parent_folder_id', 'id');
    }

    public function getBreadcrumbData()
    {
        $links = [];
        $parent = $this;
        while($parent){
            $links[] = $parent;
            $parent = $parent->parentFolder;
        }
        return array_reverse($links);
    }

    public function folders() {
        return $this->hasMany(Folder::class, 'parent_folder_id', 'id');
    }

    public function foldersRecursive()
    {
        return $this->folders()->with('foldersRecursive');
    }

    public function deleteWithChildren()
    {
        foreach ($this->folders as $subfolder) {
            $subfolder->deleteWithChildren();
        }

        $files = File::where('folder_id', $this->id)->get();
        $files->each(function ($file) {
            Storage::delete('/uploads/'.$file->file_name);
            $file->delete();
        });
        $this->delete();
    }
}
