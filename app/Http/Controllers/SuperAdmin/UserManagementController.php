<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\Panel\Auth\UserService;
use App\Services\Panel\LineService;
use App\Services\Panel\UserLevelCategoryService;
use App\ViewModel\UserLevelCategory\SetUserLevelCategoryWithCategoryIdViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    private UserService $userService;
    private UserLevelCategoryService $userLevelCategory;
    private LineService $lineService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->userLevelCategory = new UserLevelCategoryService();
        $this->lineService = new LineService();
    }

    public function AddNewUser(){
        $lines = $this->lineService->getAllLines();
        $data['lines'] = $lines;
        return view('panel.super_admin.user_management.add_new_user', $data);
    }

    public function GetUserInfo($email){
        $userInfo = $this->userService->GetUserInfoByEmail($email);
        return $userInfo;
    }

    public function GetMasterOfCategory($categoryId){
        $result = $this->userService->GetMastersOrGrandMasterOfCategory($categoryId);
        return response()->json($result);
    }

    public function AddNewUserLevelCategoryAction(Request $request, $masterId = 0){
        $validator = Validator::make($request->toArray(),[
            'user_id'=>'required|exists:users,id',
            'category'=>'required|exists:categories,id',
            'master'=>'required|exists:user_level_categories,id',
        ]);
        if(!$validator->getMessageBag()->isEmpty()){
            return redirect()->back()->withErrors($validator->errors(), 'validator');
        }
        $currentTime = date("Y-m-d H:i:s");
        $model = new SetUserLevelCategoryWithCategoryIdViewModel();
        $model->setCategoryId($request->category);
        $model->setParentId($request->master);
        $model->setUserId($request->user_id);
        $model->setExpireDate(date('Y-m-d H:i:s', strtotime('+3 month', strtotime($currentTime))));

        $result = $this->userLevelCategory->SetUserLevelCategoryWithCategoryId($model);
        if($result){
            return redirect()->back()->with("state", "1");
        }

        return redirect()->back()->with("state", "0");
    }
}
