<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Panel\Course\CourseFileService;
use App\Services\Panel\Course\CourseService;
use App\Services\Panel\Course\CoursFileeService;
use App\ViewModel\Course\SaveCourseFileViewModel;
use App\ViewModel\Course\SaveCourseViewModel;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private CourseService $courseService;
    private CourseFileService $courseFileService;

    public function __construct(CourseService $courseService, CourseFileService $courseFileService)
    {
        $this->courseService = $courseService;
        $this->courseFileService = $courseFileService;
    }

    public function GetCoursesOfLevelCategoryId($level_category_id){
        $data['courses'] = $this->courseService->GetCourseOfLevelCategoryId(levelCategoryId: $level_category_id);
        return view('panel.master.course.index', $data);
    }

    public function SaveCourse(Request $request){
        try {
            $viewModel = new SaveCourseViewModel();
            $viewModel->setTitle($request->title);
            $viewModel->setDescription($request->description);
            if(isset($request->id))
                $viewModel->setId($request->id);

            $this->courseService->SaveCourse($viewModel);
            return redirect()->back()->with('state', 1);
        }catch (\Exception $exception){
            return redirect()->back()->with('state', 0);
        }
    }

    public function GetCourseDetails($courseId){
        $data['course'] = $this->courseService->GetCourseDetails($courseId);
        if(empty($data['course']))
            abort(404);
        
        return view('panel.master.course.details', $data);
    }

    public function CourseFiles($courseId){
        $data['files'] = $this->courseFileService->GetCourseFiles($courseId);
        $data['course_id'] = $courseId;
        return view('panel.master.course.files', $data);
    }

    public function DeleteCourseFile($id){
        $this->courseFileService->DeleteCourseFile($id);
        return redirect()->back()->with('state', 1);
    }

    public function SaveCourseFile(Request $request){
        $viewModel = new SaveCourseFileViewModel();
        $viewModel->setCourseId($request->course_id);
        $viewModel->setTitle($request->title);
        $viewModel->setFile($request->file);
        $this->courseFileService->SaveCourseFile($viewModel);
        return redirect()->back()->with('state', 1);
    }
}