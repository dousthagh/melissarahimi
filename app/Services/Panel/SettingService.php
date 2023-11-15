<?php

namespace App\Services\Panel;

use App\Models\MasterFiles;
use App\Models\Setting;
use App\Services\UploaderService;
use App\ViewModel\Setting\SaveSettingViewModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    private UploaderService $uploaderService;

    public function __construct()
    {
        $this->uploaderService = new UploaderService();
    }

    public function GetSetting()
    {
        $haveLogo = false;
        $haveSideImage = false;

        $setting = Setting::first();
        if ($setting) {
            $haveLogo = $setting->logo_file_path != null;
            $haveSideImage = $setting->side_image_path != null;
        }

        return [
            'have_logo' => $haveLogo,
            'have_setting' => $haveSideImage
        ];
    }


    public function SaveSetting(SaveSettingViewModel $model)
    {
        $setting = Setting::first();
        if ($setting == null)
            $setting = new Setting();
        $savePath = "setting";

        if ($model->getLogo() != null) {
            $logo = $this->uploaderService->saveAndResizeImage(
                imageFile: $model->getLogo(),
                savePath: $savePath
            );
            $setting->logo_file_path = $logo['original'];
        }

//        if ($model->getSideImage() != null) {
//            $result = $this->uploaderService->saveAndResizeImage(
//                imageFile: $model->getSideImage(),
//                savePath: $savePath
//            );
//            $setting->side_image_path = $result['original'];
//        }

        $setting->save();
    }


    public function GetLogoFile()
    {
        $file = Setting::first();
        if(!$file)
            abort(403);
        return $this->GetFile($file->logo_file_path, "setting", true);
    }

    public function GetSideImageFile()
    {
        $file = Setting::first();
        if(!$file)
            abort(403);
        return $this->GetFile($file->side_image_path, "setting", true);
    }

    private function GetFile($fileName, $path, $thumb){
        if (!$fileName)
            abort(403);

        $mainFileName = $thumb ? "thumb-".$fileName : $fileName;
        $path = Storage::path($path.DIRECTORY_SEPARATOR.$mainFileName);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);

        $type = File::mimeType($path);

        $response = Response::make($file);

        $response->header("Content-Type", $type);


        return $response;
    }

}
