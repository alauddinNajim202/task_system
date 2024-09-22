<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
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

    // get all task from tasks table
    static public  function get_all_tasks(){
        return self::select('tasks.*')
                   
                    ->orderBy('id', 'desc')
                    ->whereNull('tasks.deleted_at')
                    ->get();

    }

    static function get_task_by_category($category_id){

        return Task::where('category_id', $category_id)->get();
        
    }

    // get single taks
    static function get_single_task($id){
        return self::find($id);
    }




    // relation ship with users table
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    


}
