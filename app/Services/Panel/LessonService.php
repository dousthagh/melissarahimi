<?php

namespace App\Services\Panel;

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

class LessonService
{

    private UploaderService $uploaderService;
    private MessageService $messageService;
    private UserLevelCategoryService $userLevelCategoryService;

    public function __construct()
    {
        ini_set('max_execution_time', -1);

        $this->uploaderService = new UploaderService();
        $this->messageService = new MessageService();
        $this->userLevelCategoryService = new UserLevelCategoryService();

    }

    public function GetLessonsByUserLevelCategoryId($userLevelCategoryId)
    {
        $userId = auth()->id();
        $mainUserLevelCategory = $this->userLevelCategoryService->getFirstUserLevelCategory($userId, $userLevelCategoryId);
        $result = $mainUserLevelCategory
            ->levelCategory
            ->lessons()
            ->with(["files" => function ($table) {
                $table->where("lesson_files.is_active", 1);
            }])
            ->select([
                DB::raw("$mainUserLevelCategory->id as user_level_category_id"),
                "lessons.*",
                DB::raw("(select count(id) from passed_lessons where user_level_category_id = $mainUserLevelCategory->id and lesson_id = lessons.id) as is_passed")
            ])
            ->get();

        $firstNotPassed = false;
        foreach ($result as $item) {
            $item['show_video'] = false;

            if ($item->is_passed)
                $item['show_video'] = true;
            else {
                if (!$firstNotPassed) {
                    $item['show_video'] = true;
                    $firstNotPassed = true;
                }
            }
        }
        return $result;
    }

    public function GetLessonDetailsForCurrentUserLevelCategoryId($lessonId, $userLevelCategoryId)
    {
        $userId = auth()->id();
        if (!$this->isPassedLesson($lessonId, $userId, $userLevelCategoryId))
            abort(403);

        return Lesson::where("id", $lessonId)
            ->with(["files" => function ($table) {
                $table->where("lesson_files.is_active", 1);
                $table->orderBy("lesson_files.sort_order");
            }])
            ->with(["passedLessons" => function ($table) use ($userLevelCategoryId) {
                $table->where("passed_lessons.user_level_category_id", $userLevelCategoryId);
            }])
            ->first();
    }

    public function GetLessonFileAddressBySecretKey($secretKey, $userLevelCategoryId, $privateKey)
    {
        ini_set('memory_limit', '-1');
        $userId = auth()->id();
        $lessonFiles = LessonFile::join("lessons", "lessons.id", "=", "lesson_files.lesson_id")
            ->join("level_categories", "lessons.level_category_id", "=", "level_categories.id")
            ->join("user_level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->leftJoin("passed_lessons", "passed_lessons.lesson_id", "=", DB::raw("lessons.id and passed_lessons.user_level_category_id = $userLevelCategoryId"))
            ->where("user_level_categories.id", $userLevelCategoryId)
            ->where("secret_key", $secretKey)
            ->whereRaw("(passed_lessons.id is not null ||
       lesson_files.lesson_id = (select min(id)
                                 from lessons
                                 where lessons.level_category_id = level_categories.id
                                   and lessons.id not in (select passed_lessons.lesson_id
                                                          from passed_lessons
                                                          where passed_lessons.user_level_category_id = user_level_categories.id)))")
            ->where("user_level_categories.user_id", $userId)
            ->first(["lesson_files.file_path", "lesson_files.postfix", "lesson_files.lesson_id"]);
        if ($lessonFiles)
            return $lessonFiles;

        return null;
    }

    public function GetLessonSamples($lessonId, $userLevelCategoryId, $myWork = true)
    {
        $userId = auth()->id();
        if($myWork){
            $startUserLevelCategory = $this->userLevelCategoryService->getFirstUserLevelCategory($userId, $userLevelCategoryId, false);
            $userLevelCategoryId = $startUserLevelCategory->id;
        }

        $result = LessonSampleWork::join("user_level_categories", "user_level_categories.id", "=", "lesson_sample_works.user_level_category_id")
            ->join("user_level_categories as user_level_categories_parent", "user_level_categories_parent.id", "=", "user_level_categories.parent_id")
            ->where("user_level_categories_parent.is_active", 1)
            ->where("user_level_categories.id", $userLevelCategoryId)
            ->where("lesson_sample_works.lesson_id", $lessonId)
            ->select([
                "lesson_sample_works.*",
                DB::raw("(case status when 'new' then 'در انتظار بررسی' when 'accepted' then 'تایید شده' when 'rejected' then 'رد شده' else 'نا مشخص' end) as status_title"),
                "user_level_categories_parent.user_id as master_user_id",
                "user_level_categories.user_id as simple_user_id",
            ]);
        if ($myWork) {
            $result = $result->where("user_level_categories.user_id", $userId);
        } else {
            $result = $result->where("user_level_categories_parent.user_id", $userId);
        }
        return $result->orderByDesc("id")->get();
    }

    public function UserCanSendSampleWork($lessonId, $userLevelCategoryId)
    {
        $isExistsUserLevelCategoryOfCurrentUser = UserLevelCategory::where("user_id", auth()->id())
            ->where("id", $userLevelCategoryId)->first();

        if (!$isExistsUserLevelCategoryOfCurrentUser)
            abort(403);

        if( $isExistsUserLevelCategoryOfCurrentUser->expire_date < date("Y-m-d H:i:s")){
            abort(503);
        }
        if (!$isExistsUserLevelCategoryOfCurrentUser->is_active || $isExistsUserLevelCategoryOfCurrentUser->start_user_level_category_id != null) {
            return false;
        }


        $latestLessonSampleWorkStatus = LessonSampleWork::where("lesson_id", $lessonId)
            ->where("user_level_category_id", $userLevelCategoryId)
            ->orderByDesc("id")->first();

        if ($latestLessonSampleWorkStatus && $latestLessonSampleWorkStatus->status == "rejected") {
            return true;
        } elseif (!$latestLessonSampleWorkStatus)
            return true;

        return false;
    }

    public function SendSampleWork(NewSampleWorkViewModel $model)
    {
        $userLevelCategory = UserLevelCategory::join("user_level_categories as user_level_category_parent", "user_level_category_parent.id", "=", "user_level_categories.parent_id")
            ->where("user_level_categories.id", $model->getUserLevelCategoryId())
            ->where("user_level_categories.user_id", auth()->id())
            ->where("user_level_categories.is_active", 1)
            ->where("user_level_category_parent.is_active", 1)
            ->select("user_level_category_parent.user_id as user_level_category_parent_user_id")
            ->first();
        if (!$userLevelCategory)
            abort(403);

        $lessonSampleWork = new LessonSampleWork();
        $lessonSampleWork->lesson_id = $model->getLessonId();
        $lessonSampleWork->user_level_category_id = $model->getUserLevelCategoryId();
        $lessonSampleWork->description = $model->getDescription();

        $destinationPath = "sample_work" . DIRECTORY_SEPARATOR . $model->getUserLevelCategoryId() . DIRECTORY_SEPARATOR . $model->getLessonId();
        $uploadResult = $this->uploaderService->saveAndResizeImage($model->getFile(), $destinationPath);

        $lessonSampleWork->file_path = $uploadResult['original'];
        $lessonSampleWork->thumbnail_path = $uploadResult['thumbnail'];

        DB::beginTransaction();
        $lessonSampleWork->save();

        $senderUserId = auth()->id();
        $receivedUserId = $userLevelCategory->user_level_category_parent_user_id;
        $title = __('message.send_sample_work_title');
        $content = __('message.send_sample_work_content');
        $sendMessageModel = new SendMessageViewModel();
        $sendMessageModel->setSenderUserId($senderUserId);
        $sendMessageModel->setReceivedUserId($receivedUserId);
        $sendMessageModel->setContent($content);
        $sendMessageModel->setTitle($title);
        $sendMessageModel->setType('system');
        $sendMessageModel->setLink(route('user_level_category.master.my_student.sample_work.details', ['lessonId' => $model->getLessonId(), 'userLevelCategoryId' => $model->getUserLevelCategoryId()]));
        $this->messageService->sendInnerNotification($sendMessageModel);
        DB::commit();

    }

    public function GetSampleWorkImage($sampleWorkId, $isThumbnail = false)
    {
        $lessonSampleWork = LessonSampleWork::where("lesson_sample_works.id", $sampleWorkId)
            ->join("user_level_categories", "user_level_categories.id", "=", "lesson_sample_works.user_level_category_id")
            ->join("user_level_categories as user_level_category_parent", "user_level_categories.parent_id", "=", "user_level_category_parent.id")
            ->whereRaw("(user_level_categories.user_id=" . auth()->id() . " or user_level_category_parent.user_id=" . auth()->id() . ")")
            ->first("lesson_sample_works.*");
        if (!$lessonSampleWork)
            abort(403);

        if ($isThumbnail)
            $filePath = $lessonSampleWork->thumbnail_path;
        else
            $filePath = $lessonSampleWork->file_path;

        $path = Storage::path("sample_work" . DIRECTORY_SEPARATOR . $lessonSampleWork->user_level_category_id . DIRECTORY_SEPARATOR . $lessonSampleWork->lesson_id . DIRECTORY_SEPARATOR . $filePath);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);


        return $response;
    }



    public function GetLessonFile($key, $userLevelCategoryId, $privateKey)
    {
        $lessonFile = $this->GetLessonFileAddressBySecretKey($key, $userLevelCategoryId, $privateKey);

        if (!$lessonFile)
            abort(403);


        $path = Storage::path("lesson" . DIRECTORY_SEPARATOR . $lessonFile->lesson_id . DIRECTORY_SEPARATOR . $lessonFile->file_path);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file);

        $response->header("Content-Type", $type);


        return $response;
    }

    private function isPassedLesson($lessonId, $userId, $userLevelCategoryId)
    {
        return Lesson::join("level_categories", "lessons.level_category_id", "=", "level_categories.id")
            ->join("user_level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->leftJoin("passed_lessons", "passed_lessons.lesson_id", "=", DB::raw("lessons.id and passed_lessons.user_level_category_id = $userLevelCategoryId"))
            ->where("user_level_categories.id", $userLevelCategoryId)
            ->where("lessons.id", $lessonId)
            ->whereRaw("(passed_lessons.id is not null ||
       lessons.id = (select min(id)
                                 from lessons
                                 where lessons.level_category_id = level_categories.id
                                   and lessons.id not in (select passed_lessons.lesson_id
                                                          from passed_lessons
                                                          where passed_lessons.user_level_category_id = user_level_categories.id)))")
            ->where("user_level_categories.user_id", $userId)
            ->exists();
    }


    public function GetLessonOfLevelCategoryId($levelCategoryId){
        return Lesson::where("level_category_id", $levelCategoryId)
            ->where("is_active", 1)
            ->get();
    }

    public function GetLessonDetails($lessonId){
        return Lesson::where("id", $lessonId)
            ->first();
    }

    public function GetLessonFiles($lessonId){
        return LessonFile::where("lesson_id", $lessonId)
            ->get();
    }

    public function SaveLesson(SaveLessonViewModel $model){
        if($model->getId() > 0)
            $lesson = Lesson::find($model->getId());
        else
            $lesson = new Lesson();

        $lesson->title = $model->getTitle();
        $lesson->description = $model->getDescription();
        $lesson->save();
    }

    public function SaveLessonFile(SaveLessonFileViewModel $model){
        $lessonFile = new LessonFile();
        $lessonFile->lesson_id = $model->getLessonId();
        $lessonFile->title = $model->getTitle();
        $destinationAddress = "lesson".DIRECTORY_SEPARATOR .$model->getLessonId();
        $lessonFile->secret_key = Str::uuid();
        $result = $this->uploaderService->saveFile($model->getFile(), $destinationAddress);
        $lessonFile->file_path = $result['file_name'];
        $lessonFile->postfix = ".".$result['postfix'];

        $lessonFile->save();
    }

    public function DeleteLessonFile($id){
        $lessonFile = LessonFile::find($id);
        $fullPath = "lesson".DIRECTORY_SEPARATOR.$lessonFile->lesson_id;
        $this->uploaderService->unlink($fullPath, $lessonFile->file_path);
        $lessonFile->delete();
    }

}
