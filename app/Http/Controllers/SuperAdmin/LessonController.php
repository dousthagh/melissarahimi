<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\Panel\LessonService;
use App\Services\SecretKeyService;
use App\ViewModel\Lesson\SaveLessonFileViewModel;
use App\ViewModel\Lesson\SaveLessonViewModel;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private LessonService $lessonService;
    private SecretKeyService $secretKeyService;

    /**
     * @param LessonService $lessonService
     */
    public function __construct(LessonService $lessonService, SecretKeyService $secretKeyService)
    {
        $this->lessonService = $lessonService;
        $this->secretKeyService = $secretKeyService;
    }

    public function GetLessonsOfLevelCategoryId($level_category_id){
        $data['lessons'] = $this->lessonService->GetLessonOfLevelCategoryId(levelCategoryId: $level_category_id);
        return view('panel.super_admin.lesson.index', $data);
    }

    public function GetLessonsDetails($lessonId){
        $data['lesson'] = $this->lessonService->GetLessonDetails($lessonId);
        return view('panel.super_admin.lesson.details', $data);
    }

    public function GetLessonDetailsWithContentForPreviewInAdmin($lessonId)
    {
        $data['details'] = $this->lessonService->GetLessonDetailsWithContent($lessonId);
        $data['key'] = $this->secretKeyService->generateAndSave();

        return view("panel.super_admin.lesson.preview", $data);
    }

    public function GetLessonFileAddressBySuperAdmin($key, $privateKey)
    {
        return $this->lessonService->GetLessonFile($key, 0, $privateKey, true);
    }


    public function SaveLesson(Request $request){
        try {
            $viewModel = new SaveLessonViewModel();
            $viewModel->setTitle($request->title);
            $viewModel->setDescription($request->description);
            if(isset($request->id))
                $viewModel->setId($request->id);

            $viewModel->setWithSampleWork( $request->with_sample_work == 'on');
            $this->lessonService->SaveLesson($viewModel);
            return redirect()->back()->with('state', 1);
        }catch (\Exception $exception){
            return redirect()->back()->with('state', 0);
        }
    }

    public function LessonFile($lessonId){
        $data['files'] = $this->lessonService->GetLessonFiles($lessonId);
        $data['lesson_id'] = $lessonId;
        return view('panel.super_admin.lesson.files', $data);
    }

    public function DeleteLessonFile($id){
        $this->lessonService->DeleteLessonFile($id);
        return redirect()->back()->with('state', 1);
    }

    public function SaveLessonFile(Request $request){
        $viewModel = new SaveLessonFileViewModel();
        $viewModel->setLessonId($request->lesson_id);
        $viewModel->setTitle($request->title);
        $viewModel->setFile($_FILES['file']);
        $this->lessonService->SaveLessonFile($viewModel);
        return redirect()->back()->with('state', 1);
    }
}
