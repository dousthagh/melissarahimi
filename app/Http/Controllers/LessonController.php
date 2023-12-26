<?php

namespace App\Http\Controllers;

use App\Models\LessonSampleWork;
use App\Models\SecretKey;
use App\Services\Panel\LessonService;
use App\Services\Panel\UserLevelCategoryService;
use App\Services\SecretKeyService;
use App\Services\Video\VideoService;
use App\ViewModel\Lesson\NewSampleWorkViewModel;
use Doctrine\Deprecations\VerifyDeprecationsTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    private LessonService $lessonService;
    private SecretKeyService $secretKeyService;
    private UserLevelCategoryService $userLevelCategoryService;
    private VideoService $videoService;

    public function __construct()
    {
        $this->lessonService = new LessonService();
        $this->userLevelCategoryService = new UserLevelCategoryService();
        $this->secretKeyService = new SecretKeyService();
        $this->videoService = new VideoService();
    }

    public function GetLessonList($user_level_category_id)
    {
        $data['lessons'] = $this->lessonService->GetLessonsByUserLevelCategoryId($user_level_category_id);
        $data['userLevelCategoryDetails'] = $this->userLevelCategoryService->getUserLevelCategoryDetails($user_level_category_id);

        $data['count'] = $this->userLevelCategoryService->countOfStudentOfUserLevelCategory($data['userLevelCategoryDetails']->parent_id);
        return view("panel.lesson.list", $data);
    }

    public function GetLessonDetails($userLevelCategoryId, $lessonId)
    {
        $data['userLevelCategoryDetails'] = $this->userLevelCategoryService->getUserLevelCategoryDetails($userLevelCategoryId);
        if($data['userLevelCategoryDetails']->expired)
            abort(503);
        $data['details'] = $this->lessonService->GetLessonDetailsForCurrentUserLevelCategoryId($lessonId, $userLevelCategoryId);
        $data['key'] = $this->secretKeyService->generateAndSave();
        $data['userLevelCategoryId'] = $userLevelCategoryId;
        $data['is_passed'] = $this->lessonService->isPassedLesson($lessonId, auth()->id(), $userLevelCategoryId);
        if (!$data['details'])
            abort(403);


        $data['user_name']= auth()->user()->name;
        return view("panel.lesson.details", $data);
    }



    public function GetLessonFileAddressBySecretKey($key, $userLevelCategoryId, $privateKey)
    {
        return $this->lessonService->GetLessonFile($key, $userLevelCategoryId, $privateKey, false);
    }

    public function ShowVideo($videoId){
        return Redirect::to($this->videoService->generateVideoAddress($videoId));
    }


    public function ShowSampleWorkImage($sampleWorkId, $isThumbnail)
    {
        return $this->lessonService->GetSampleWorkImage($sampleWorkId, $isThumbnail);
    }

    public function ShowMasterSampleWorkImage($sampleWorkId, $isThumbnail)
    {
        return $this->lessonService->GetMasterSampleWorkImage($sampleWorkId, $isThumbnail);
    }

    public function MySampleWorkList($lessonId, $userLevelCategoryId)
    {
        $data['canSendSampleWork'] = $this->lessonService->UserCanSendSampleWork($lessonId, $userLevelCategoryId);
        $data['lessonId'] = $lessonId;
        $data['userLevelCategoryId'] = $userLevelCategoryId;
        $data['samples'] = $this->lessonService->GetLessonSamples($lessonId, $userLevelCategoryId);

        return view('panel.lesson.sample', $data);
    }

    public function SendSampleWork(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'lesson_id' => 'required|exists:lessons,id',
            'user_level_category_id' => 'required|exists:user_level_categories,id',
            'file' => 'required|mimes:png,jpg,jpeg',
        ]);
        if (!$validator->getMessageBag()->isEmpty()) {
            return redirect()->back()->withErrors($validator->errors(), 'validator');
        }
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'user_level_category_id' => 'required|exists:user_level_categories,id',
            'file' => 'required|mimes:png,jpg,jpeg',
        ]);
        $viewModel = new NewSampleWorkViewModel();
        $viewModel->setDescription($request->description);
        $viewModel->setLessonId($request->lesson_id);
        $viewModel->setUserLevelCategoryId($request->user_level_category_id);
        $viewModel->setFile($_FILES['file']);

        $this->lessonService->SendSampleWork($viewModel);
        return redirect()->back();

    }


}
