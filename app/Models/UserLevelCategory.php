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

}
