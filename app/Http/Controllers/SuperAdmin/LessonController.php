<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\Panel\LessonService;
use App\ViewModel\Lesson\SaveLessonFileViewModel;
use App\ViewModel\Lesson\SaveLessonViewModel;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private LessonService $courseService;

    /**
     * @param LessonService $lessonService
     */
    public function __construct(LessonService $lessonService)
    {
        $this->courseService = $lessonService;
    }

    public function GetLessonsOfLevelCategoryId($level_category_id){
        $data['lessons'] = $this->courseService->GetLessonOfLevelCategoryId(levelCategoryId: $level_category_id);
        return view('panel.super_admin.lesson.index', $data);
    }

    public function GetLessonsDetails($lessonId){
        $data['lesson'] = $this->courseService->GetLessonDetails($lessonId);
        return view('panel.super_admin.lesson.details', $data);
    }

    public function SaveLesson(Request $request){
        try {
            $viewModel = new SaveLessonViewModel();
            $viewModel->setTitle($request->title);
            $viewModel->setDescription($request->description);
            if(isset($request->id))
                $viewModel->setId($request->id);

            $this->courseService->SaveLesson($viewModel);
            return redirect()->back()->with('state', 1);
        }catch (\Exception $exception){
            return redirect()->back()->with('state', 0);
        }
    }

    public function LessonFile($lessonId){
        $data['files'] = $this->courseService->GetLessonFiles($lessonId);
        $data['lesson_id'] = $lessonId;
        return view('panel.super_admin.lesson.files', $data);
    }

    public function DeleteLessonFile($id){
        $this->courseService->DeleteLessonFile($id);
        return redirect()->back()->with('state', 1);
    }

    public function SaveLessonFile(Request $request){
        $viewModel = new SaveLessonFileViewModel();
        $viewModel->setLessonId($request->lesson_id);
        $viewModel->setTitle($request->title);
        $viewModel->setFile($request->file);
        $this->courseService->SaveLessonFile($viewModel);
        return redirect()->back()->with('state', 1);
    }
}
