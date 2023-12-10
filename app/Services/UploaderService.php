<?php


namespace App\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use PHPUnit\Exception;

class UploaderService
{
    public function saveFile($file, $savePath)
    {
        try {
            $destinationAddress = Storage::path($savePath) . DIRECTORY_SEPARATOR;
            $imagesName = now()->getTimestampMs() . str_replace('-','',Str::uuid()) . '.' . $file->getClientOriginalExtension();
//            if (!file_exists($destinationAddress)) {
//                dd("here");
//                mkdir($destinationAddress);
//            }
            $file->move($destinationAddress, $imagesName);

            return [
                'file_name' => $imagesName,
                'postfix' => $file->getClientOriginalExtension()
            ];
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function saveAndResizeImage($imageFile, $savePath, $width = 80, $height = null)
    {
        if (is_null($imageFile))
            return null;

        try {
            $image = $imageFile;
            $imageName = date('ymdhis') . '.' . $image->getClientOriginalExtension();
            $imageNameCopy = date('ymdhis') . '.' . $image->getClientOriginalExtension();
            $destinationAddress = Storage::path($savePath) . DIRECTORY_SEPARATOR;

            $image->move($destinationAddress, $imageName);
            $thumbnailSize = 'thumb-';
            $thumbnailImageName = $thumbnailSize . $imageNameCopy;
            File::copy($destinationAddress . $imageNameCopy, $destinationAddress . $thumbnailImageName);
            Image::make($destinationAddress . $thumbnailImageName)
                ->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($destinationAddress . $thumbnailImageName);

            return [
                'original' => $imageName,
                'thumbnail' => 'thumb-' . $imageNameCopy,
            ];
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }


    public function unlink($path, $fileName)
    {
        try {
            $fullPath = Storage::path($path).DIRECTORY_SEPARATOR.$fileName;
            if (file_exists($fullPath)){
                if (unlink($fullPath)){
                    return true;
                }

            }

            return false;
        } catch (Exception $exception) {
            return false;
        }
    }
}
