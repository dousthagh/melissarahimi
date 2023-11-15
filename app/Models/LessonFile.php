<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonFile extends Model
{
    use HasFactory;

    protected $hidden = ["secret_key"];

    public function Lesson(){
        return $this->belongsTo(Lesson::class);
    }
}
