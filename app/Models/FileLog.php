<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileLog extends Model
{
    //
    protected $table = 'file_logs';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // public static function insertLog($message) {
    //     $log = new FileLog();
    //     $log->message = $message;
    //     $log->save();
    // }
}
