<?php

namespace App\Services\Panel;

use App\Models\Level;
use App\Services\bucket\BucketService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class LevelService
{
    
    public function GetLevelLogoImage($levelKey)
    {
        $bucketService = new BucketService();
        $level = Level::where("key", $levelKey)->first();
        if (!$level)
            abort(403);
        $levelCategory = $level->levelCategories()->first();
        $path = "app/level_category/".$levelCategory->logo_file_address;
        return $bucketService->getFile($path);
    }
}
