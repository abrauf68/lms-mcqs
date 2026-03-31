<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function fillBlanks()
    {
        return $this->hasOne(FillBlank::class);
    }

    public function matchPairs()
    {
        return $this->hasMany(MatchPair::class);
    }

    public function hotspot()
    {
        return $this->hasOne(Hotspot::class);
    }
}
