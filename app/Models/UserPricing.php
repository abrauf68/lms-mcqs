<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pricing_id',
        'transaction_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
