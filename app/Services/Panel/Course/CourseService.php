<?php

namespace App\Services\Panel\Course;

use App\Models\Course;
use App\ViewModel\Course\SaveCourseViewModel;

class CourseService
{

    public function GetCourseOfLevelCategoryId($levelCategoryId){
        return Course::where("level_category_id", $levelCategoryId)
            ->where("is_active", 1)
            ->get();
    }

    public function GetCourseDetails($courseId){
        return Course::where("id", $courseId)
            ->first();
    }

    public function SaveCourse(SaveCourseViewModel $model){
        if($model->getId() > 0)
            $course = Course::find($model->getId());
        else{
            $course = new Course();
            $course->level_category_id = $model->getLevelCategoryId();
        }

        $course->title = $model->getTitle();
        $course->description = $model->getDescription();
        $course->save();
    }

}
