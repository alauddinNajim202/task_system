<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserLevel extends Model
{
    use HasFactory;
    use softDeletes;

    protected $fillable = [
        'user_id',
        'level',
    ];




    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
