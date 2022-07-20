<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leagues extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
        'sports_id' => 'string',
        'icon' => 'string',
    ];

    protected $fillable = [
        'icon',
        'name',
        'sports_id',
    ];
}
