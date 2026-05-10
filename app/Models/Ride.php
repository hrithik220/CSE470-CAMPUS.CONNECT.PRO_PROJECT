<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'user_id', 'pickup_location', 'destination_area', 
        'departure_time', 'available_seats', 'cost_per_seat', 'status'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requests() {
        return $this->hasMany(RideRequest::class);
    }
}