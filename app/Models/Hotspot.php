<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'image',
        'x',
        'y',
        'width',
        'height',
        'radius',
    ];
}
