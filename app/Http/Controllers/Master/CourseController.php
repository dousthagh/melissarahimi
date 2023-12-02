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
        $data['level_category_id'] = $level_category_id;
        return view('panel.super_admin.course.index', $data);
    }

    public function SaveCourse(Request $request){
        try {
            $viewModel = new SaveCourseViewModel();
            $viewModel->setTitle($request->title);
            if((int)$request->level_category_id > 0)
                $viewModel->setLevelCategoryId((int)$request->level_category_id);
            $viewModel->setDescription($request->description);
            if(isset($request->id))
                $viewModel->setId($request->id);

                $this->courseService->SaveCourse($viewModel);
            return redirect()->back()->with('state', 1);
        }catch (\Exception $exception){
            dd($exception->getMessage());
            return redirect()->back()->with('state', 0);
        }
    }

    public function GetCourseDetails($courseId){
            $data['course'] = $this->courseService->GetCourseDetails($courseId);
            if(empty($data['course']))
                abort(404);

        return view('panel.super_admin.course.details', $data);
    }

    public function NewCourse($levelCategoryId){
        $data['level_category_id']= $levelCategoryId;
        return view('panel.super_admin.course.new', $data);
    }

    public function CourseFiles($courseId){
        $data['files'] = $this->courseFileService->GetCourseFiles($courseId);
        $data['course_id'] = $courseId;
        return view('panel.super_admin.course.files', $data);
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
