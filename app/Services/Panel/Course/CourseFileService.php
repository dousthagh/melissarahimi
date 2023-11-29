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

class CourseFileService
{

    private UploaderService $uploaderService;

    public function __construct()
    {
        ini_set('max_execution_time', -1);

        $this->uploaderService = new UploaderService();
    }


    public function SaveCourseFile(SaveCourseFileViewModel $model){
        $courseFile = new CourseFile();
        $courseFile->course_id = $model->getCourseId();
        $courseFile->title = $model->getTitle();
        $destinationAddress = "course".DIRECTORY_SEPARATOR .$model->getCourseId();
        $courseFile->secret_key = Str::uuid();
        $result = $this->uploaderService->saveFile($model->getFile(), $destinationAddress);
        $courseFile->file_path = $result['file_name'];
        $courseFile->postfix = ".".$result['postfix'];

        $courseFile->save();
    }

    public function DeleteCourseFile($id){
        $courseFile = CourseFile::find($id);
        $fullPath = "course".DIRECTORY_SEPARATOR.$courseFile->lesson_id;
        $this->uploaderService->unlink($fullPath, $courseFile->file_path);
        $courseFile->delete();
    }

    public function GetCourseFiles($courseId){
        return CourseFile::where("course_id", $courseId)
            ->get();
    }

}
