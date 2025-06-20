<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'event_date',
        'location',
        'price',
        'quota',
        'remaining_quota',
        'status',
        'category_id'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'price' => 'integer',
        'quota' => 'integer',
        'remaining_quota' => 'integer',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
