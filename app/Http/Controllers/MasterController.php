<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Panel\LessonService;
use App\Services\Panel\MasterService;
use App\Services\Panel\UserLevelCategoryService;
use App\ViewModel\Master\AddMasterFilesViewMode;
use App\ViewModel\UserLevelCategory\GetUserLevelCategoryStudentFilterViewModel;
use App\ViewModel\UserLevelCategory\SetUserLevelCategoryWithoutCategoryIdViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterController extends Controller
{
    private UserLevelCategoryService $userLevelCategoryService;
    private LessonService $lessonService;
    private MasterService $masterService;

    public function __construct()
    {
        $this->userLevelCategoryService = new UserLevelCategoryService();
        $this->lessonService = new LessonService();
        $this->masterService = new MasterService();

    }

    public function GetMyStudentWithUserLevelCategoryId($parentUserLevelCategoryId)
    {
        $pageNumber = 1;
        if (\request()->has("page"))
            $pageNumber = \request()->get("page");

        $data['allowedLevels'] = $this->userLevelCategoryService->GetLevelsOfParentUserLevelCategory($parentUserLevelCategoryId);

        $filterViModel = new GetUserLevelCategoryStudentFilterViewModel();
        $filterViModel->setId(\request()->get("filter_id"));
        $filterViModel->setEmail(\request()->get("filter_email"));
        $filterViModel->setLevelId(\request()->get("filter_level"));
        $filterViModel->setName(\request()->get("filter_name"));
        $data['students'] = $this->userLevelCategoryService->GetMyStudentsWithUserLevelCategoryParent($parentUserLevelCategoryId, $pageNumber, $filterViModel);

        $data['parentUserLevelCategoryId'] = $parentUserLevelCategoryId;
        $data['filter']['id'] = \request()->get("filter_id");
        $data['filter']['name'] = \request()->get("filter_name");
        $data['filter']['email'] = \request()->get("filter_email");
        $data['filter']['level'] = \request()->get("filter_level");
        return view("panel.master.my_students_list", $data);
    }

    public function GetMyStudentSampleWorkLessonList($userLevelCategoryId)
    {
        $data['lessons'] = $this->userLevelCategoryService->GetStudentSampleWorkLessonList($userLevelCategoryId);
        $data['userLevelCategoryId'] = $userLevelCategoryId;
        return view("panel.master.my_students_sample_work_lesson_list", $data);
    }

    public function MyStudentSampleWorkList($lessonId, $userLevelCategoryId)
    {
        $data['canSendSampleWork'] = false;
        $data['lessonId'] = $lessonId;
        $data['userLevelCategoryId'] = $userLevelCategoryId;
        $data['samples'] = $this->lessonService->GetLessonSamples($lessonId, $userLevelCategoryId, false);

        return view('panel.lesson.sample', $data);
    }

    public function ApplyCommentOnSampleWork(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'sample_work_id' => 'required|exists:lesson_sample_works,id',
            'status' => 'required|in:accepted,rejected',
        ]);
        if (!$validator->getMessageBag()->isEmpty()) {
            return redirect()->back()->withErrors($validator->errors(), 'validator');
        }
        $this->userLevelCategoryService->AcceptOrRejectSampleWork($request->sample_work_id, $request->status, $request->master_description);
        return redirect()->back();
    }

    public function SetUserLevelCategoryWithoutCategoryId(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'user_id' => 'required|exists:users,id',
            'level_id' => 'required|exists:levels,key',
            'parent_user_level_category_id' => 'required|exists:user_level_categories,id',
        ]);
        if (!$validator->getMessageBag()->isEmpty()) {
            return redirect()->back()->withErrors($validator->errors(), 'validator');
        }

        $viewModel = new SetUserLevelCategoryWithoutCategoryIdViewModel();
        $viewModel->setUserId($request->user_id);
        $viewModel->setNewLevelKey($request->level_id);
        $viewModel->setParentUserLevelCategoryId($request->parent_user_level_category_id);

        $result = $this->userLevelCategoryService->SetUserLevelCategoryWithoutCategoryId($viewModel);
        if ($result)
            return redirect()->back()->with("state", "1");
        else
            return redirect()->back()->with("state", "0");
    }

    public function MasterDetails($userLevelCategoryId)
    {
        $data['master_details'] = $this->masterService->GetMasterDetails($userLevelCategoryId);
        $data['master_files'] = $this->masterService->GetMasterFiles($userLevelCategoryId);
        return view('panel.master.details', $data);
    }

    public function GetMasterFileForDownload($fileId)
    {
        return $this->masterService->GetMasterFileForDownload($fileId);
    }

    public function MasterList()
    {
        $data['masters'] = $this->masterService->GetMasterList();
        return view('panel.super_admin.master.list', $data);
    }

    public function MasterUpdateDetails($userLevelCategoryId)
    {
        $data['user_level_category_id'] = $userLevelCategoryId;
        $data['files'] = $this->masterService->GetMasterFiles($userLevelCategoryId);
        return view('panel.super_admin.master.update_details', $data);
    }

    public function DeleteMasterFile($id)
    {
        $userLevelCategoryId = $this->masterService->DeleteMasterFile($id);
        return redirect()->route('super_admin.master.update_details', ['user_level_category_id'=> $userLevelCategoryId])->with('state', "1");
    }

    public function SaveMasterFile(Request $request)
    {
        $viewModel = new AddMasterFilesViewMode();
        $viewModel->setFile($request->file);
        $viewModel->setUserLevelCategoryId($request->user_level_category_id);
        $this->masterService->SaveMasterFiles($viewModel);
        return redirect()->route('super_admin.master.update_details', ['user_level_category_id'=> $request->user_level_category_id])->with('state', "1");
    }

}
