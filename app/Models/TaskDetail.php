<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TaskDetail extends Model
{
    use HasFactory;

    use softDeletes;

    protected $fillable = [
        'user_id',
        'task_id',
        
        'points',
        'end_date',
    ];




    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

}
