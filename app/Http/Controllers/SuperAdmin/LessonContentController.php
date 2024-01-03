<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\bucket\BucketService;
use App\Services\Panel\LessonContentService;
use App\Services\SecretKeyService;
use App\Services\Video\VideoService;
use App\ViewModel\Lesson\LessonContent\SaveContentViewModel;
use Illuminate\Http\Request;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class LessonContentController extends Controller
{
    private LessonContentService $contentService;
    private SecretKeyService $secretKeyService;
    public function __construct(LessonContentService $contentService, SecretKeyService $secretKeyService)
    {
        $this->contentService = $contentService;
        $this->secretKeyService = $secretKeyService;
    }

    public function GetContentOfLesson($lessonId){
        $contents = $this->contentService->GetContentOfLesson($lessonId);
        $data['lesson_id'] = $lessonId;
        $data['contents'] = $contents;
        return view('panel.super_admin.lesson.lesson_content.index', $data);
    }



    public function SaveContent(Request $request){
        try {
            $viewModel = new SaveContentViewModel();
            if((int)$request->lesson_id > 0)
                $viewModel->setLessonId((int)$request->lesson_id);
            $viewModel->setContent($request->description);
            if(isset($request->id))
                $viewModel->setId($request->id);
            if(isset($_FILES['file']))
                $viewModel->setFiles($_FILES['file']);
            $viewModel->setDeletedFilesId($request->delete_files);


            $this->contentService->SaveContent($viewModel);
            return redirect()->back()->with('state', 1);
        }catch (\Exception $exception){
            return redirect()->back()->with('state', 0);
        }
    }

    public function GetContentDetails($contentId){
            $data['content'] = $this->contentService->GetContentDetails($contentId);
            if(empty($data['content']))
                abort(404);

        $data['key'] = $this->secretKeyService->generateAndSave();

        return view('panel.super_admin.lesson.lesson_content.details', $data);
    }

    public function NewContent($lessonId){
        $data['lesson_id']= $lessonId;
        return view('panel.super_admin.lesson.lesson_content.new', $data);
    }


    public function GetLessonContentFileAddressBySecretKey($key, $lessonContentId, $privateKey)
    {
        return $this->contentService->GetLessonContentFile($key, $lessonContentId, $privateKey);
    }
    public function GetLessonContentFileAddressBySecretKeyByAdmin($key, $lessonContentId, $privateKey)
    {
        return $this->contentService->GetLessonContentFile($key, $lessonContentId, $privateKey, true);
    }

    public function Delete($id, $lessonId)
    {
        $this->contentService->DeleteContent($id);
        return redirect()->route('super_admin.lesson.content.index', ['lesson_id'=>$lessonId]);
    }



//    public function CourseFiles($courseId){
//        $data['files'] = $this->courseFileService->GetCourseFiles($courseId);
//        $data['course_id'] = $courseId;
//        return view('panel.super_admin.course.files', $data);
//    }
//
//    public function DeleteCourseFile($id){
//        $this->courseFileService->DeleteCourseFile($id);
//        return redirect()->back()->with('state', 1);
//    }
//
//    public function SaveCourseFile(Request $request){
//        $viewModel = new SaveCourseFileViewModel();
//        $viewModel->setCourseId($request->course_id);
//        $viewModel->setTitle($request->title);
//        $viewModel->setFile($request->file);
//        $this->courseFileService->SaveCourseFile($viewModel);
//        return redirect()->back()->with('state', 1);
//    }
}
