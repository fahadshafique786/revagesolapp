<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCredentials extends Model
{
    use HasFactory;

//    protected $casts = [
//    ];

    protected $fillable = [
        'app_detail_id',
        'package_id',
        'secret_key',
        'stream_key'
    ];

}
