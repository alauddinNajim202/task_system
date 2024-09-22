<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TaskAssign extends Model
{
    use HasFactory;
    use softDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'assignee_to',
        'description',
        'status',
        'due_date',
    ];

   

    static function get_task($id){

        return Task::find($id);
        
    }

    // get single taks
    static function get_single_task($id){
        return self::find($id);
    }
}
