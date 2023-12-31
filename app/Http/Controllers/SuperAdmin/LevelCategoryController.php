<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LevelCategory;
use App\Services\Panel\LevelCategoryService;
use App\Services\Panel\UserLevelCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class LevelCategoryController extends Controller
{
    private LevelCategoryService $levelCategoryService;
    private UserLevelCategoryService $userLevelCategoryService;


    /**
     * @param LevelCategoryService $levelCategoryService
     */
    public function __construct(LevelCategoryService $levelCategoryService, UserLevelCategoryService $userLevelCategoryService)
    {
        $this->levelCategoryService = $levelCategoryService;
        $this->userLevelCategoryService = $userLevelCategoryService;
    }

    public function GetCurrentMasterLevelCategories($parentUserLevelCategoryId){
       $data['levels'] = $this->levelCategoryService->GetCurrentMasterLevelCategoris($parentUserLevelCategoryId);
       $data['parent_user_level_category_id'] = $parentUserLevelCategoryId;
       return view('panel.master.level_categories.my_levels_list', $data);
    }

    public function GetAllLevelCategories(){
        $data['level_categories'] = $this->levelCategoryService->GetAllLevelCategories();
        return view('panel.super_admin.level_categories.index', $data);
    }

    public function GetLevelCategoryLogoAsThumbnail($levelCategoryId){
       return $this->levelCategoryService->GetLevelCategoryLogoFile($levelCategoryId);
    }
    public function GetLevelCategoryLogo($levelCategoryId){
        return $this->levelCategoryService->GetLevelCategoryLogoFile($levelCategoryId, false);
    }

    public function ChangeLogo($levelCategoryId){
        $data['level_category_id'] = $levelCategoryId;
        return view('panel.super_admin.level_categories.change_logo', $data);
    }

    public function SaveLogo(Request $request){
        $this->levelCategoryService->SaveLogo($request->id, $_FILES['file']);
        return redirect()->route('super_admin.level_category.index')->with('state', 1);
    }

}
