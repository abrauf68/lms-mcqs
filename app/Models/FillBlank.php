<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FillBlank extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'image',
        'blank_text',
        'correct_answer'
    ];
}
