<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    public function levelCategories(){
        return $this->belongsTo(LevelCategory::class);
    }

    public function files(){
        return $this->hasMany(LessonFile::class);
    }

    public function passedLessons(){
        return $this->hasMany(PassedLesson::class);
    }
}
