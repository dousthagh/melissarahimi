<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    public function categories(){
        return $this->belongsToMany(Category::class, "level_categories", "level_id");
    }

    public function levelCategories(){
        return $this->hasMany(LevelCategory::class);
    }

}
