<?php

namespace App\Services\Panel;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\Lesson;
use App\Models\LessonContent;
use App\Models\LessonFile;
use App\Models\LessonSampleWork;
use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\SecretKey;
use App\Models\UserLevelCategory;
use App\Services\UploaderService;
use App\Services\Panel\Message\MessageService;
use App\Services\SecretKeyService;
use App\ViewModel\Course\SaveCourseViewModel;
use App\ViewModel\Lesson\LessonContent\SaveContentFileViewModel;
use App\ViewModel\Lesson\LessonContent\SaveContentViewModel;
use App\ViewModel\Message\SendMessageViewModel;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\True_;
use Ramsey\Uuid\Guid\Guid;

class LessonContentService
{

    public function GetContentOfLesson($lessonId){
        return LessonContent::where("lesson_id", $lessonId)
            ->where("is_active", 1)
            ->get();
    }

    public function GetContentDetails($contentId){
        return LessonContent::where("id", $contentId)
            ->first();
    }

    public function SaveContent(SaveContentViewModel $model){
        if($model->getId() > 0)
            $content = LessonContent::find($model->getId());
        else{
            $content = new LessonContent();
            $content->lesson_id = $model->getLessonId();
        }

        $content->content = $model->getContent();
        $content->save();
    }

}
