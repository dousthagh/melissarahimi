<?php

namespace App\Services\Panel;

use App\Models\LessonSampleWork;
use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\PassedLesson;
use App\Models\User;
use App\Models\UserLevelCategory;
use App\Services\bucket\BucketService;
use App\Services\Panel\Auth\IdentityService;
use App\Services\Panel\Message\MessageService;
use App\ViewModel\Message\SendMessageViewModel;
use App\ViewModel\Message\SendChangeLevelMessageViewModel;
use App\ViewModel\UserLevelCategory\GetUserLevelCategoryStudentFilterViewModel;
use App\ViewModel\UserLevelCategory\SetUserLevelCategoryWithCategoryIdViewModel;
use App\ViewModel\UserLevelCategory\SetUserLevelCategoryWithoutCategoryIdViewModel;
use Illuminate\Support\Facades\DB;

class UserLevelCategoryService
{
    private IdentityService $identityService;
    private MessageService $messageService;
    private BucketService $bucketService;

    public function __construct()
    {
        $this->identityService = new IdentityService();
        $this->messageService = new MessageService();
        $this->bucketService = new BucketService();
    }

    public function SetUserLevelCategoryWithCategoryId(SetUserLevelCategoryWithCategoryIdViewModel $model, $levelCategoryId = 0, $roleName = "Student")
    {

        $userLevelCategory = new UserLevelCategory();
        $userLevelCategory->user_id = $model->getUserId();
        if ($levelCategoryId > 0)
            $userLevelCategory->level_category_id = $levelCategoryId;
        else {
            $userLevelCategory->level_category_id = $this->getFirstLevelIdInCategory($model->getCategoryId());
        }

        $userLevelCategory->parent_id = $model->getParentId();
        if ($roleName == 'Student') {
            $currentTime = date("Y-m-d H:i:s");
            $userLevelCategory->expire_date = date('Y-m-d H:i:s', strtotime('+3 month', strtotime($currentTime)));;
        } else {
            $userLevelCategory->expire_date = $model->getExpireDate();
        }
        $userLevelCategory->start_user_level_category_id = $model->getStartUserLevelCategoryId();
        $parentUser = UserLevelCategory::where("id", $model->getParentId())->first();
        $user = User::where("id", $model->getUserId())->first();
        $generatedCode = substr($roleName, 0, 1) . $user->id . substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1) . $parentUser->id . substr($parentUser->first_name, 0, 1) . substr($parentUser->last_name, 0, 1);
        $userLevelCategory->code = $generatedCode;

        DB::beginTransaction();
        UserLevelCategory::join("level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->where("is_active", 1)
            ->where("level_categories.category_id", $model->getCategoryId())
            ->where("user_id", $model->getUserId())
            ->update(["is_active" => 0]);

        $this->identityService->addRoleToUser($model->getUserId(), $roleName);
        DB::commit();

        if ($userLevelCategory->save())
            return $userLevelCategory;

        return null;
    }

    public function SetUserLevelCategoryWithoutCategoryId(SetUserLevelCategoryWithoutCategoryIdViewModel $model)
    {
        try {
            $currentUserLevelCategory = UserLevelCategory::where("user_id", $model->getUserId())
                ->where("parent_id", $model->getParentUserLevelCategoryId())
                ->where("is_active", 1)
                ->first();
            if (!$currentUserLevelCategory) {
                abort(500);
            }
            $parentUserLevelCategory = UserLevelCategory::where("user_level_categories.id", $model->getParentUserLevelCategoryId())
                ->where("user_id", auth()->id())
                ->where("user_level_categories.is_active", 1)
                ->join("level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
                ->select("level_categories.category_id", "user_level_categories.id as user_level_category_id")
                ->first();
            if (!$parentUserLevelCategory)
                abort(403);

            $levelCategory = LevelCategory::where("category_id", $parentUserLevelCategory->category_id)
                ->join("levels", "levels.id", "=", "level_categories.level_id")
                ->where("levels.key", $model->getNewLevelKey())
                ->select("level_categories.id as level_category_id", "levels.title as level_title", "levels.key as level_key", "levels.sort_order as level_order")
                ->first();
            if (!$levelCategory)
                abort(403);

            $isExistsNewUserLevelCategory = UserLevelCategory::where("parent_id", $model->getParentUserLevelCategoryId())
                ->where("user_id", $model->getUserId())
                ->where("level_category_id", $levelCategory->level_category_id)
                ->where("is_active", 1)
                ->exists();
            if ($isExistsNewUserLevelCategory)
                return false;

            $firstUserLeveCategory = UserLevelCategory::where("parent_id", $model->getParentUserLevelCategoryId())
                ->where("user_id", $model->getUserId())
                ->orderBy("id")
                ->first();


            $setUserLevelCategoryViewModel = new SetUserLevelCategoryWithCategoryIdViewModel();
            $setUserLevelCategoryViewModel->setUserId($model->getUserId());
            $setUserLevelCategoryViewModel->setParentId($model->getParentUserLevelCategoryId());
            $setUserLevelCategoryViewModel->setCategoryId($parentUserLevelCategory->category_id);
            $setUserLevelCategoryViewModel->setStartUserLevelCategoryId($firstUserLeveCategory->id);
            $result = $this->SetUserLevelCategoryWithCategoryId(
                model: $setUserLevelCategoryViewModel,
                levelCategoryId: $levelCategory->level_category_id,
                roleName: $levelCategory->level_title
            );
        } catch (\Exception $ex) {
            return false;
        }

        if ($result) {
            if ($levelCategory->level_order < $currentUserLevelCategory->levelCategory->level->sort_order) {
                $this->sendReduceLevelEmail($model->getUserId(), $levelCategory->level_key, $levelCategory->level_title, $result->code);
            } else {

                $this->sendNewLevelPromotedEmail($model->getUserId(), $levelCategory->level_key, $levelCategory->level_title, $result->code);
            }
        }
        return $result;
    }

    public function GetMyStudentsWithUserLevelCategoryParent($userLevelCategoryId, $pageNumber = 1, GetUserLevelCategoryStudentFilterViewModel $categoryStudentFilterViewModel = null)
    {
        $result = UserLevelCategory::where("user_level_categories.id", $userLevelCategoryId)
            ->join("user_level_categories as user_level_category_child", "user_level_category_child.parent_id", "=", "user_level_categories.id")
            ->join("level_categories", "user_level_category_child.level_category_id", "=", "level_categories.id")
            ->join("levels", "level_categories.level_id", "=", "levels.id")
            ->join("users", "users.id", "=", "user_level_category_child.user_id")
            ->where("user_level_categories.user_id", auth()->id())
            ->where("user_level_categories.is_active", 1)
            ->where("user_level_category_child.is_active", 1)
            ->where("users.is_active", 1)
            ->groupBy("user_level_category_child.user_id")
            ->orderBy("levels.sort_order")
            ->select(
                "users.id",
                "users.name",
                "users.email",
                "levels.sort_order as level_order",
                "levels.title as level_title",
                "user_level_category_child.id as user_level_category_child_id"
            );

        if ($categoryStudentFilterViewModel != null) {
            if ($categoryStudentFilterViewModel->getId())
                $result = $result->where("users.id", $categoryStudentFilterViewModel->getId());
            if ($categoryStudentFilterViewModel->getEmail())
                $result = $result->where("users.email", $categoryStudentFilterViewModel->getEmail());
            if ($categoryStudentFilterViewModel->getName())
                $result = $result->where("users.name", "like", "%" . $categoryStudentFilterViewModel->getName() . "%");
            if ($categoryStudentFilterViewModel->getLevelId())
                $result = $result->where("levels.key", $categoryStudentFilterViewModel->getLevelId());
        }
        $result = $result->paginate(10, ['*'], 'page', $pageNumber)
            ->withQueryString();
        return $result;
    }

    public function GetStudentSampleWorkLessonList($userLevelCategoryId)
    {
        return LessonSampleWork::join("user_level_categories", "user_level_categories.id", "=", "lesson_sample_works.user_level_category_id")
            ->join("user_level_categories as user_level_category_parent", "user_level_categories.parent_id", "=", "user_level_category_parent.id")
            ->join("lessons", "lessons.id", "=", "lesson_sample_works.lesson_id")
            ->where("user_level_category_parent.user_id", auth()->id())
            ->where("lesson_sample_works.user_level_category_id", $userLevelCategoryId)
            ->groupBy("lesson_sample_works.lesson_id")
            ->orderByDesc("lesson_sample_works.id")
            ->select("lessons.id as lesson_id", "lessons.title as lesson_title")
            ->get();
    }

    public function AcceptOrRejectSampleWork($sampleWorkId, $status, $description, $file = null)
    {
        if ($status == 'accepted') {
            $this->AcceptSampleWork($sampleWorkId, $description);
        } else {
            $this->RejectSampleWork($sampleWorkId, $description, $file);
        }
    }

    public function AcceptSampleWork($sampleWorkId, $description)
    {
        $sampleWork = LessonSampleWork::join("user_level_categories", "user_level_categories.id", "=", "lesson_sample_works.user_level_category_id")
            ->join("user_level_categories as user_level_category_parent", "user_level_category_parent.id", "=", "user_level_categories.parent_id")
            ->where("user_level_category_parent.is_active", 1)
            ->where("user_level_categories.is_active", 1)
            ->where("user_level_category_parent.user_id", auth()->id())
            ->where("lesson_sample_works.id", $sampleWorkId)
            ->select([
                "user_level_categories.user_id as user_level_category_user_id",
                "lesson_sample_works.user_level_category_id",
                "lesson_sample_works.lesson_id"
            ])->first();
        if (!$sampleWork) {
            abort(403);
        }
        DB::beginTransaction();
        LessonSampleWork::where("id", $sampleWorkId)->update(["status" => "accepted", "master_description" => $description]);
        $passedLesson = new PassedLesson();
        $passedLesson->lesson_id = $sampleWork->lesson_id;
        $passedLesson->user_level_category_id = $sampleWork->user_level_category_id;
        $passedLesson->save();


        $sendMessageModel = new SendMessageViewModel();
        $sendMessageModel->setSenderUserId(auth()->id());
        $sendMessageModel->setReceivedUserId($sampleWork->user_level_category_user_id);
        $sendMessageModel->setContent(__('message.accept_sample_work_content'));
        $sendMessageModel->setTitle(__('message.accept_sample_work_title'));
        $sendMessageModel->setType('system');
        $sendMessageModel->setLink(route('user_level_category.lesson.sample_work', ['lessonId' => $sampleWork->lesson_id, 'userLevelCategoryId' => $sampleWork->user_level_category_id]));
        $this->messageService->sendInnerNotification($sendMessageModel);

        Db::commit();
    }

    public function RejectSampleWork($sampleWorkId, $description, $file = null)
    {
        $sampleWork = LessonSampleWork::join("user_level_categories", "user_level_categories.id", "=", "lesson_sample_works.user_level_category_id")
            ->join("user_level_categories as user_level_category_parent", "user_level_category_parent.id", "=", "user_level_categories.parent_id")
            ->where("user_level_category_parent.is_active", 1)
            ->where("user_level_categories.is_active", 1)
            ->where("user_level_category_parent.user_id", auth()->id())
            ->where("lesson_sample_works.id", $sampleWorkId)
            ->select(
                "user_level_categories.user_id as user_level_category_user_id",
                "lesson_sample_works.user_level_category_id",
                "lesson_sample_works.lesson_id"
            )->first();
        if (!$sampleWork) {
            abort(403);
        }
        DB::beginTransaction();
        $masterFilePath = null;
        if ($file != null) {
            $currentSampleWork = LessonSampleWork::find($sampleWorkId, ['user_level_category_id', 'lesson_id']);
            $destinationPath = "sample_work/" . "/" . $currentSampleWork->user_level_category_id . "/" . $currentSampleWork->lesson_id . "/" . $file['name'];
            $uploadResult = $this->bucketService->uploadPartOfFile($file, $destinationPath);
            if (!$uploadResult)
                abort(500);
            $masterFilePath = $file['name'];
        }

        LessonSampleWork::where("id", $sampleWorkId)->update([
            "status" => "rejected",
            "master_description" => $description,
            "master_file_path" => $masterFilePath,
        ]);

        $sendMessageModel = new SendMessageViewModel();
        $sendMessageModel->setSenderUserId(auth()->id());
        $sendMessageModel->setReceivedUserId($sampleWork->user_level_category_user_id);
        $sendMessageModel->setContent(__('message.reject_sample_work_content'));
        $sendMessageModel->setTitle(__('message.reject_sample_work_title'));
        $sendMessageModel->setType('system');
        $sendMessageModel->setLink(route('user_level_category.lesson.sample_work', ['lessonId' => $sampleWork->lesson_id, 'userLevelCategoryId' => $sampleWork->user_level_category_id]));
        $this->messageService->sendInnerNotification($sendMessageModel);

        Db::commit();
    }

    public function GetLevelsOfParentUserLevelCategory($userLevelCategoryId)
    {
        $currentUserLevelCategory = UserLevelCategory::where("user_level_categories.id", $userLevelCategoryId)
            ->where("user_id", auth()->id())
            ->where("user_level_categories.is_active", 1)
            ->join("level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->join("levels", "levels.id", "=", "level_categories.level_id")
            ->first(["level_categories.category_id", "levels.sort_order"]);
        if (!$currentUserLevelCategory)
            abort(403);

        $allowedLevelsOfCategory = LevelCategory::join("levels", "levels.id", "=", "level_categories.level_id")
            ->where("levels.sort_order", "<", $currentUserLevelCategory->sort_order)
            ->where("level_categories.category_id", $currentUserLevelCategory->category_id)
            ->select("levels.*")
            ->get();

        return $allowedLevelsOfCategory;
    }


    private function getFirstLevelIdInCategory($categoryId)
    {
        $firstLevelOfAcademy = Level::where("sort_order", 1)->first();
        $levelCategory = LevelCategory::where("level_id", $firstLevelOfAcademy->id,)
            ->where("category_id", $categoryId)->select("id")->first();
        return $levelCategory->id;
    }

    public function getFirstUserLevelCategory($userId, $userLevelCategoryId, $checkIsActive = true)
    {
        $userLevelCategory = UserLevelCategory::where("user_level_categories.id", $userLevelCategoryId)
            ->with(['levelCategory' => function ($tblLevelCategory) {
                $tblLevelCategory->with('category');
            }])
            ->with(['parent' => function ($tbl) {
                $tbl->with("parentUser");
            }])
            ->where("user_level_categories.user_id", $userId);
        if ($checkIsActive) {
            $userLevelCategory = $userLevelCategory->where("user_level_categories.is_active", 1);
        }
        $userLevelCategory = $userLevelCategory->first();
        if (!$userLevelCategory)
            abort(403);

        $result = $userLevelCategory;
        $result['is_start'] = true;
        if ($userLevelCategory->start_user_level_category_id != null) {
            $result = UserLevelCategory::where("id", $userLevelCategory->start_user_level_category_id)
                ->first();
            $result['is_start'] = false;
        }

        if (!$result)
            abort(403);

        $result['expired'] = false;
        if ($result->expire_date < date("Y-m-d H:i:s")) {
            $result['expired'] = true;
        }
        $result['category_title'] = $userLevelCategory->levelCategory->category->title;
        $result['category_description'] = $userLevelCategory->levelCategory->category->description;
        return $result;
    }

    public function countOfStudentOfUserLevelCategory($userLevelCategoryId)
    {
        $count = UserLevelCategory::where("parent_id", $userLevelCategoryId)
            ->where("is_active", 1)
            ->groupBy("user_id")
            ->select(['id'])->get()->count();
        return $count;
    }

    public function getUserLevelCategoryDetails($userLevelCategoryId)
    {
        return $this->getFirstUserLevelCategory(auth()->id(), $userLevelCategoryId, false);
    }

    private function sendNewLevelPromotedEmail($userId, $levelKey, $levelName, $code)
    {
        $sendNewLevelPromotedMessageViewModel = $this->getSendNewLevelPromotedMessageViewModel($userId, $levelKey, $levelName, $code);
        $this->messageService->sendWelcomeToNewLevelMessage($sendNewLevelPromotedMessageViewModel);
    }
    private function sendReduceLevelEmail($userId, $levelKey, $levelName, $code)
    {
        $sendNewLevelPromotedMessageViewModel = $this->getSendNewLevelPromotedMessageViewModel($userId, $levelKey, $levelName, $code);
        $this->messageService->sendReduceLevelMessage($sendNewLevelPromotedMessageViewModel);
    }

    /**
     * @param $userId
     * @param $levelKey
     * @param $levelName
     * @return SendChangeLevelMessageViewModel
     */
    private function getSendNewLevelPromotedMessageViewModel($userId, $levelKey, $levelName, $code): SendChangeLevelMessageViewModel
    {
        $user = User::where("id", $userId)
            ->first();

        $sendChangeLevelMessageViewModel = new SendChangeLevelMessageViewModel();
        $sendChangeLevelMessageViewModel->setUserName($user->name);
        $sendChangeLevelMessageViewModel->setLevelCode($code);
        $sendChangeLevelMessageViewModel->setLevelKey($levelKey);
        $sendChangeLevelMessageViewModel->setLevelName($levelName);
        $sendChangeLevelMessageViewModel->setReceiverEmail($user->email);
        $sendChangeLevelMessageViewModel->setReceiverUserId($user->id);
        $sendChangeLevelMessageViewModel->setSenderUserId(auth()->id());
        return $sendChangeLevelMessageViewModel;
    }
}
