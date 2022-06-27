<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servers extends Model
{
    use HasFactory;


    protected $fillable = [
        'sports_id',
        'leagues_id',
        'name',
        'link',
        'isHeader',
        'isPremium',
    ];


}
