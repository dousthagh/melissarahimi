<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\Panel\AllStudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private AllStudentService $allStudentService;
    public function __construct(AllStudentService $allStudentService){
        $this->allStudentService = $allStudentService;
    }
    public function GetAllStudentWithUserLevelCategoryId($parentUserLevelCategoryId)
    {
        $data['students'] = $this->allStudentService->GetAllStudentWithMaster($parentUserLevelCategoryId);
        return view("panel.super_admin.master.all_student.all_students_list", $data);
    }
    public function GetAllStudentWithUserLevelCategoryIdHistories($parentUserLevelCategoryId, $levelCategoryId, $userId)
    {
        $data['students'] = $this->allStudentService->GetHistories($parentUserLevelCategoryId, $levelCategoryId, $userId);
        return view("panel.super_admin.master.all_student.all_students_history_list", $data);
    }
}
