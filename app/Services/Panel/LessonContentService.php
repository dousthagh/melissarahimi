<?php

namespace App\Services\Panel;

use App\Http\Controllers\Controller;
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
    private SecretKeyService $secretKeyService;


    public function __construct(SecretKeyService $secretKeyService)
    {
        ini_set('max_execution_time', -1);

        $this->uploaderService = new UploaderService();
        $this->secretKeyService = $secretKeyService;
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

    public function GetLessonContentFileAddressBySecretKey($secretKey, $lessonContentId, $privateKey)
    {
        ini_set('memory_limit', '-1');
        $isValidPrivateKey = $this->secretKeyService->isCorrectKey($privateKey);
        if(!$isValidPrivateKey){
            abort(403);
        }
        $userId = auth()->id();
        $lessonContentFile = LessonContentFiles::where("secret_key", $secretKey)
            ->where("lesson_content_id", $lessonContentId)
            ->where("user_level_categories.user_id", $userId)
            ->join("lesson_contents", "lesson_contents.id", "=", "lesson_content_files.lesson_content_id")
            ->join("lessons", "lessons.id", "=", "lesson_contents.lesson_id")
            ->join("level_categories", "level_categories.id", "=", "lessons.level_category_id")
            ->join("user_level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->first(["lesson_content_files.file_path", "lesson_content_files.postfix", "lesson_content_files.lesson_content_id"]);


        if ($lessonContentFile)
            return $lessonContentFile;

        return null;
    }

    public function GetLessonContentFile($key, $lessonContentId, $privateKey)
    {
        $lessonContentFile = $this->GetLessonContentFileAddressBySecretKey($key, $lessonContentId, $privateKey);
        if (!$lessonContentFile) {
            abort(403);
        }

        $path = Storage::path("content" . DIRECTORY_SEPARATOR . $lessonContentId . DIRECTORY_SEPARATOR . $lessonContentFile->file_path);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);
        $size = File::size($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Content-Disposition", "inline; filename=\"$type\"");
        $response->header("Content-Length", $size);

        // Allow cross-origin requests if needed
        // $response->header("Access-Control-Allow-Origin", "*");
        // $response->header("Access-Control-Allow-Methods", "GET, OPTIONS");


        return $response;

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
                for ($i = 0; $i<count($model->getFiles()); $i++){
                    $file = $model->getFiles()[$i];
                    $destinationAddress = "content" . DIRECTORY_SEPARATOR . $content->id;
                    $result = $this->uploaderService->saveFile($file, $destinationAddress);

                    $contentFile = new LessonContentFiles();
                    $contentFile->lesson_content_id = $content->id;
                    $contentFile->file_path = $result['file_name'];
                    $contentFile->postfix = $result['postfix'];
                    $contentFile->secret_key = Str::uuid();;

                    $contentFile->save();
                }
            }
            DB::commit();

        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }

    public function DeleteContent($id){
        $content = LessonContent::find($id);
        $files = $content->files;
        $content->delete();
        foreach ($files as $file){
            $this->deleteFile($file->id);
        }
    }



    private function deleteFile($id){
        $lessonContentFile = LessonContentFiles::find($id);
        $fullPath = "content".DIRECTORY_SEPARATOR.$lessonContentFile->lesson_content_id;
        $this->uploaderService->unlink($fullPath, $lessonContentFile->file_path);
        $lessonContentFile->delete();
    }


}
