<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelCategory extends Model
{
    use HasFactory;

    public function userLevelCategories(){
        return $this->hasMany(UserLevelCategory::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function level(){
        return $this->belongsTo(Level::class);
    }
    public function lessons(){
        return $this->hasMany(Lesson::class);
    }
}
