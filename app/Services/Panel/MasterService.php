<?php

namespace App\Services\Panel;

use App\Models\MasterFiles;
use App\Models\UserLevelCategory;
use App\Services\bucket\BucketService;
use App\ViewModel\Master\AddMasterFilesViewMode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;
use function Sodium\add;

class MasterService
{
    private BucketService $bucketService;

    public function __construct()
    {
        $this->bucketService = new BucketService();
    }


    public function GetMasterFiles($userLevelCategoryId)
    {
        return MasterFiles::where("user_level_category_id", $userLevelCategoryId)
            ->get();
    }

    public function GetMasterDetails($userLevelCategoryId)
    {
        return UserLevelCategory::where("user_level_categories.id", $userLevelCategoryId)
            ->join("users", "users.id", "=", "user_level_categories.user_id")
            ->select("users.*")
            ->first();
    }

    public function GetMasterFileForDownload($fileId)
    {
        $file = MasterFiles::where("id", $fileId)->first();
        if (!$file)
            abort(403);

        $path = Storage::path("master_files" . DIRECTORY_SEPARATOR . $file->user_level_category_id . DIRECTORY_SEPARATOR . $file->file_path);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file);

        $response->header("Content-Type", $type);


        return $response;
    }

    public function GetMasterList()
    {
        return UserLevelCategory::join("level_categories", "level_categories.id", "=", "user_level_categories.level_category_id")
            ->join("users", "users.id", "=", "user_level_categories.user_id")
            ->join("levels", "levels.id", "=", "level_categories.level_id")
            ->join("categories", "categories.id", "=", "level_categories.category_id")
            ->join("lines", "categories.line_id", "=", "lines.id")
            ->where("levels.sort_order", "5")
            ->select([
                "user_level_categories.id as user_level_category_id",
                "users.id as user_id",
                "users.name as user_name",
                DB::raw("concat(users.first_name, ' ', users.last_name) as user_full_name"),
                "users.email as user_email",
                "categories.title as category_title",
                "lines.title as line_title"
            ])->get();
    }

    public function DeleteMasterFile($id)
    {
        $masterFile = MasterFiles::find($id);
        if (!$masterFile)
            abort(404);
        $userLevelCategoryId = $masterFile->user_level_category_id;
        $path = "master_files" . "/" . $userLevelCategoryId . "/" . $masterFile->file_path;
        $this->bucketService->Delete($path);
        $masterFile->delete();
        return $userLevelCategoryId;
    }

    public function SaveMasterFiles(AddMasterFilesViewMode $addMasterFilesViewMode)
    {
        $userLevelCategory = UserLevelCategory::find($addMasterFilesViewMode->getUserLevelCategoryId());
        $savePath = "master_files/" . $addMasterFilesViewMode->getUserLevelCategoryId()."/".$addMasterFilesViewMode->getFile()['name'];
        $result = $this->bucketService->uploadPartOfFile($addMasterFilesViewMode->getFile(), $savePath);
        if(!$result)
        abort(500);
        $fileName = $addMasterFilesViewMode->getFile()['name'];
        $postfix = $addMasterFilesViewMode->getFile()['type'];
        $masterFile = new MasterFiles();
        $masterFile->user_level_category_id = $addMasterFilesViewMode->getUserLevelCategoryId();
        $masterFile->user_id = $userLevelCategory->user_id;
        $masterFile->file_path = $fileName;
        $masterFile->file_type = $postfix;
        $masterFile->save();
    }
}
