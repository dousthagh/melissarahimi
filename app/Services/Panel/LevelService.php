<?php

namespace App\Services\Panel;

use App\Models\Level;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class LevelService
{
    public function GetLevelLogoImage($levelKey)
    {
        $level = Level::where("key", $levelKey)->first();
        if (!$level)
            abort(403);
        $levelCategory = $level->levelCategories()->first();
        $path = storage_path("app".DIRECTORY_SEPARATOR."level_category".DIRECTORY_SEPARATOR.$levelCategory->logo_file_address);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);

        return $response;
    }
}
