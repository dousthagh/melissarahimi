<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Services\Panel\LevelService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LevelController extends Controller
{
    private LevelService $levelService;
    public function __construct()
    {
        $this->levelService = new LevelService();
    }

    public function GetLevelLogo($levelKey): Response
    {
        return $this->levelService->GetLevelLogoImage($levelKey);
    }
}
