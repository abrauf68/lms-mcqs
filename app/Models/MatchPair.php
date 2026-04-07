<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'left_item',
        'right_item'
    ];
}
