<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevelCategory extends Model
{
    use HasFactory;

    public function levelCategory(){
        return $this->belongsTo(LevelCategory::class);
    }

    public function user(){
        return $this->hasMany(User::class);
    }

    public function passedLessons(){
        return $this->hasMany(PassedLesson::class);
    }

    public function parent(){
        return $this->belongsTo(UserLevelCategory::class, "parent_id", "id");
    }
    public function parentUser(){
        return $this->belongsTo(User::class, "user_id", "id");
    }

}
