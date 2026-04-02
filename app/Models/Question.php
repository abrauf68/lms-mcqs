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

    public function fillBlank()
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

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function processGroup()
    {
        return $this->belongsTo(ProcessGroup::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function approach()
    {
        return $this->belongsTo(Approach::class);
    }
}
