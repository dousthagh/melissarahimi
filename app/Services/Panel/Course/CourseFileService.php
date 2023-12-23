<?php

namespace App\Services\Panel\Course;

use App\Models\CourseFile;
use App\Services\bucket\BucketService;
use App\ViewModel\Course\SaveCourseFileViewModel;
use Illuminate\Support\Str;

class CourseFileService
{

    private BucketService $bucketService;

    public function __construct()
    {
        ini_set('max_execution_time', -1);

        $this->bucketService = new BucketService();
    }


    public function SaveCourseFile(SaveCourseFileViewModel $model){
        $courseFile = new CourseFile();
        $courseFile->course_id = $model->getCourseId();
        $courseFile->title = $model->getTitle();
        $destinationAddress = "course"."/" .$model->getCourseId()."/".$model->getFile()['name'];
        $courseFile->secret_key = Str::uuid();
        $result = $this->bucketService->uploadPartOfFile($model->getFile(), $destinationAddress);
        $courseFile->file_path = $model->getFile()['name'];
        $courseFile->postfix = $model->getFile()['type'];

        $courseFile->save();
    }

    public function DeleteCourseFile($id){
        $courseFile = CourseFile::find($id);
        if(!$courseFile)
            abort(404);
        $fullPath = "course"."/".$courseFile->lesson_id."/".$courseFile->file_path;
        $this->bucketService->Delete($fullPath);
        $courseFile->delete();
    }

    public function GetCourseFiles($courseId){
        return CourseFile::where("course_id", $courseId)
            ->get();
    }

}
