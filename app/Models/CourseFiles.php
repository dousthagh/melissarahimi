<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFile extends Model
{
    use HasFactory;

    protected $hidden = ["secret_key"];

    public function Course(){
        return $this->belongsTo(Course::class);
    }
}
