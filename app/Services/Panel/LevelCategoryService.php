<?php

namespace App\Services\Panel;

use App\Models\Lesson;
use App\Models\LevelCategory;
use App\Models\UserLevelCategory;
use App\Services\bucket\BucketService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class LevelCategoryService
{
    private BucketService $bucketService;

    public function __construct(BucketService $imageService)
    {
        $this->bucketService = $imageService;
    }

    public function GetAllLevelCategories()
    {
        return LevelCategory::join("levels", "levels.id", "=", "level_categories.level_id")
            ->join("categories", "level_categories.category_id", "=", "categories.id")
            ->select([
                "levels.title as level_title",
                "levels.sort_order as level_sort_order",
                "categories.title as category_title",
                "level_categories.*"
            ])
            ->orderBy("levels.sort_order")
            ->get();
    }
    public function GetCurrentMasterLevelCategoris($parentUserLevelCategoryId)
    {
        $category = UserLevelCategory::where("user_level_categories.id", "=", $parentUserLevelCategoryId)
            ->where("user_level_categories.user_id", "=", Auth::user()->id)
            ->join("level_categories", "user_level_categories.level_category_id", "=", "level_categories.id")
            ->select(["level_categories.category_id"])
            ->first();
        if (empty($category)) {
            abort(404, "Parent id is not valid for current user");
        }
        $categoryId = $category['category_id'];
        $levels = LevelCategory::where('category_id', '=', $categoryId)
            ->join('levels', 'level_categories.level_id', '=', 'levels.id')
            ->select([
                'levels.title as level_title',
                'level_categories.id as level_category_id'
            ])->get();
        return $levels;
    }

    public function GetLevelCategoryLogoFile($levelCategoryId, $thumbnail = true)
    {
        $levelCategory = LevelCategory::find($levelCategoryId);
        $path =  "level_category" ."/" . $levelCategory->logo_file_address;
        
        return $this->bucketService->getFile($path);
    }

    public function SaveLogo($levelCategoryId, $file)
    {
        $levelCategory = LevelCategory::find($levelCategoryId);
        if (!$levelCategory)
            abort(404);
        $destinationPath = "level_category/" . $file['name'];

        $result = $this->bucketService->uploadPartOfFile($file, $destinationPath);
        $levelCategory->logo_file_address = $file['type'];
        $levelCategory->save();
    }
}
