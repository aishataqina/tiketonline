<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi many-to-many dengan Event
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function activeEvents()
    {
        return $this->hasMany(Event::class)->where('status', 'active');
    }

    // Method untuk menghitung jumlah event aktif
    public function getActiveEventsCountAttribute()
    {
        return $this->activeEvents()->count();
    }
}
