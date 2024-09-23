<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFriendship extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'is_accecpt',
        'action_date',
    ];



    // Relationship for sender (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relationship for receiver (User)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    
}
