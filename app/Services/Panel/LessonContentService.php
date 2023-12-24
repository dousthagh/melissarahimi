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
use App\Services\bucket\BucketService;
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
    private BucketService $bucketService;
    private SecretKeyService $secretKeyService;


    public function __construct(SecretKeyService $secretKeyService)
    {
        ini_set('max_execution_time', -1);

        $this->bucketService = new BucketService();
        $this->secretKeyService = $secretKeyService;
    }

    public function GetContentOfLesson($lessonId)
    {
        return LessonContent::where("lesson_id", $lessonId)
            ->where("is_active", 1)
            ->orderBy("sort_order")
            ->get();
    }

    public function GetContentDetails($contentId)
    {
        return LessonContent::where("id", $contentId)
            ->first();
    }

    public function GetLessonContentFileAddressBySecretKey($secretKey, $lessonContentId, $privateKey, $isAdmin)
    {
        ini_set('memory_limit', '-1');
        $isValidPrivateKey = $this->secretKeyService->isCorrectKey($privateKey);
        if (!$isValidPrivateKey) {
            abort(403);
        }
        $lessonContentFile = LessonContentFiles::where("secret_key", $secretKey)
            ->where("lesson_content_id", $lessonContentId)
            ->join("lesson_contents", "lesson_contents.id", "=", "lesson_content_files.lesson_content_id")
            ->join("lessons", "lessons.id", "=", "lesson_contents.lesson_id")
            ->join("level_categories", "level_categories.id", "=", "lessons.level_category_id")
            ->join("user_level_categories", "level_categories.id", "=", "user_level_categories.level_category_id");
        if (!$isAdmin) {
            $userId = auth()->id();
            $lessonContentFile = $lessonContentFile->where("user_level_categories.user_id", $userId);
        }
        $lessonContentFile = $lessonContentFile->first(["lesson_content_files.file_path", "lesson_content_files.postfix", "lesson_content_files.lesson_content_id"]);


        if ($lessonContentFile)
            return $lessonContentFile;

        return null;
    }

    public function GetLessonContentFile($key, $lessonContentId, $privateKey, $isAdmin = false)
    {
        $lessonContentFile = $this->GetLessonContentFileAddressBySecretKey($key, $lessonContentId, $privateKey, $isAdmin);
        if (!$lessonContentFile) {
            abort(403);
        }

        $path = "content" ."/" . $lessonContentId . "/" . $lessonContentFile->file_path;
        return $this->bucketService->getFile($path);
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

        if ($model->getDeletedFilesId()) {
            $fileIdThatShouldBeDeleted = json_decode($model->getDeletedFilesId());
            foreach ($fileIdThatShouldBeDeleted as $fileId) {
                $this->deleteFile($fileId);
            }
        }

        try {
            DB::beginTransaction();
            $content->save();
            if ($model->getFiles()) {
                for ($i = 0; $i < count($model->getFiles()['name']); $i++) {
                    $destinationAddress = "content" . "/" . $content->id . "/" . $model->getFiles()['name'][$i];
                    $result = $this->bucketService->uploadPartOfFile(null, $destinationAddress, $model->getFiles()['tmp_name'][$i]);
                    if (!$result)
                        abort(500);
                    $contentFile = new LessonContentFiles();
                    $contentFile->lesson_content_id = $content->id;
                    $contentFile->file_path = $model->getFiles()['name'][$i];
                    $contentFile->postfix = $model->getFiles()['type'][$i];
                    $contentFile->secret_key = Str::uuid();

                    $contentFile->save();
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }

    public function DeleteContent($id)
    {
        $content = LessonContent::find($id);
        $files = $content->files;
        $content->delete();
        foreach ($files as $file) {
            $this->deleteFile($file->id);
        }
    }


    private function deleteFile($id)
    {
        $lessonContentFile = LessonContentFiles::find($id);
        if (!$lessonContentFile)
            abort(404);
        $fullPath = "content" . "/" . $lessonContentFile->lesson_content_id . "/" . $lessonContentFile->file_path;
        $this->bucketService->Delete($fullPath);
        $lessonContentFile->delete();
    }
}
