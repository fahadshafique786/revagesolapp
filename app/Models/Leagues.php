<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leagues extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'name',
        'sports_id',
    ];
}
