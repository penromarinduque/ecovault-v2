<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $counts = [
            "files" => File::count(),
            "folders" => Folder::count(),
            "storage_used" => 0
            // "storage_used" => $this->getDirectorySize(Storage::disk("s3")->path('uploads'))
        ];
        $fileUrl = Storage::temporaryUrl('/uploads/v8i96pVUrz69afcc45ac290.pdf', now()->addMinutes(60));
        return view('dashboard', [
            "counts" => $counts,
            "fileUrl" => $fileUrl
        ]);
    }

    function getDirectorySize($path){
        $size = 0;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            // Skip dot files ( . and .. )
            if ($file->isDir()) continue;
            // Add file size to total
            $size += $file->getSize();
        }
        return $size; // Size in bytes
    }
}
