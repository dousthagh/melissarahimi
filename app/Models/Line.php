<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function levels(){
        return $this->hasMany(Lesson::class);
    }
}
