<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideNotification extends Model
{
    protected $fillable = [
        'ride_id',
        'sender_id',
        'owner_id',
        'message',
        'is_read',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}