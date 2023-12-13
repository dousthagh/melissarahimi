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
}
