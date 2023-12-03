<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonContentFiles extends Model
{
    use HasFactory;

    protected $hidden = ["secret_key"];

    public function LessonContent(){
        return $this->belongsTo(LessonContent::class);
    }
}
