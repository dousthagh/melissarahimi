<?php

namespace App\Services\Panel;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\Lesson;
use App\Models\LessonContent;
use App\Models\LessonContentFiles;
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
use Psy\Util\Json;
use Ramsey\Uuid\Guid\Guid;
use function PHPUnit\Framework\exactly;

class LessonContentService
{
    private UploaderService $uploaderService;

    public function __construct()
    {
        ini_set('max_execution_time', -1);

        $this->uploaderService = new UploaderService();
    }

    public function GetContentOfLesson($lessonId)
    {
        return LessonContent::where("lesson_id", $lessonId)
            ->where("is_active", 1)
            ->get();
    }

    public function GetContentDetails($contentId){
        return LessonContent::where("id", $contentId)
            ->first();
    }

    public function SaveContent(SaveContentViewModel $model)
    {
        if ($model->getId() > 0)
            $content = LessonContent::find($model->getId());
        else {
            $content = new LessonContent();
            $content->lesson_id = $model->getLessonId();
        }

        $content->content = $model->getContent();

        if($model->getDeletedFilesId()){
            $fileIdThatShouldBeDeleted = json_decode($model->getDeletedFilesId());
            foreach ($fileIdThatShouldBeDeleted as $fileId){
                $this->deleteFile($fileId);
            }
        }


        try {
            DB::beginTransaction();
            $content->save();
            if($model->getFiles()) {
                foreach ($model->getFiles() as $file) {
                    $destinationAddress = "content" . DIRECTORY_SEPARATOR . $content->id;
                    $result = $this->uploaderService->saveFile($file, $destinationAddress);

                    $contentFile = new LessonContentFiles();
                    $contentFile->lesson_content_id = $content->id;
                    $contentFile->file_path = $result['file_name'];
                    $contentFile->postfix = $result['postfix'];
                    $contentFile->save();
                }
            }
            DB::commit();

        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }



    private function deleteFile($id){
        $lessonContentFile = LessonContentFiles::find($id);
        $fullPath = "content".DIRECTORY_SEPARATOR.$lessonContentFile->lesson_content_id;
        $this->uploaderService->unlink($fullPath, $lessonContentFile->file_path);
        $lessonContentFile->delete();
    }


}
