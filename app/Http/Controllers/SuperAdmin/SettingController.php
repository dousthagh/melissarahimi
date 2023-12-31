<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Panel\SettingService;
use App\ViewModel\Setting\SaveSettingViewModel;
use Hamcrest\Core\Set;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private SettingService $settingService;

    public function __construct()
    {
        $this->settingService = new SettingService();
    }

    public function Index()
    {
        $setting = $this->settingService->GetSetting();
        return view('panel.super_admin.setting', $setting);
    }


    public function SaveSetting(Request $request)
    {
        $model = new SaveSettingViewModel();
        if ($request->logo != null)
            $model->setLogo($_FILES['logo']);

        if ($request->side_image != null)
            $model->setSideImage($_FILES['side_image']);

        $this->settingService->SaveSetting($model);

        return redirect()->back()->with('state', '1');
    }

    public function GetLogoFile()
    {
        return $this->settingService->GetLogoFile();
    }

    public function GetSideImageFile()
    {
        return $this->settingService->GetLogoFile();
    }
}
