<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassedLesson extends Model
{
    use HasFactory;

    public function lessons(){
        return $this->belongsTo(Lesson::class);
    }

    public function userLevelCategory(){
        return $this->belongsTo(UserLevelCategory::class);
    }
}
