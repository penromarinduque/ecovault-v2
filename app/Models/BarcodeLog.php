<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeLog extends Model
{
    //
    protected $table = 'barcode_logs';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
