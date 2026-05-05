<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideRequest extends Model
{
    protected $fillable = ['ride_id', 'user_id', 'status'];

    public function ride() {
        return $this->belongsTo(Ride::class);
    }

    public function passenger() {
        return $this->belongsTo(User::class, 'user_id');
    }
}