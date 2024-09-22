<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;





    static function get_task_by_category($category_id){

        return Task::where('category_id', $category_id)->get();
        
    }

}
