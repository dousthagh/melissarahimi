<?php

namespace App\Services\Panel\Course;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\Lesson;
use App\Models\LessonFile;
use App\Models\LessonSampleWork;
use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\SecretKey;
use App\Models\UserLevelCategory;
use App\Services\UploaderService;
use App\Services\Panel\Message\MessageService;
use App\Services\SecretKeyService;
use App\ViewModel\Course\SaveCourseFileViewModel;
use App\ViewModel\Course\SaveCourseViewModel;
use App\ViewModel\Lesson\NewSampleWorkViewModel;
use App\ViewModel\Lesson\SaveLessonFileViewModel;
use App\ViewModel\Lesson\SaveLessonViewModel;
use App\ViewModel\Message\SendMessageViewModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\True_;
use Ramsey\Uuid\Guid\Guid;

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
