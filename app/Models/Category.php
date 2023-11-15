<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function line(){
        return $this->belongsTo(Line::class);
    }

    public function levels(){
        return $this->belongsToMany(Level::class, "level_category", "category_id");
    }

    public function levelCategories(){
        return $this->hasMany(LevelCategory::class);
    }
}
