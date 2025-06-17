<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'amount',
        'payment_method',
        'status',
        'snap_token',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
