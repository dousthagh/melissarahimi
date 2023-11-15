<?php

namespace App\Services;

use App\Models\SecretKey;

class SecretKeyService
{
    public function generateAndSave()
    {
        $currentTime = date("Y-m-d H:i:s");
        $secretKey = new SecretKey();
        $secretKey->key = $this->generate();
        $secretKey->expiration_date = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($currentTime)));;
        $secretKey->save();
        return $secretKey->key;
    }

    public function useSecretKey($key)
    {
        SecretKey::where("key", $key)
            ->where("expiration_date", ">=", date("Y-m-d H:i:s"))
            ->update(['is_use'=>1]);
    }

    public function isCorrectKey($key){
        return SecretKey::where("key", $key)
            ->where("is_use", 0)
            ->where("expiration_date", ">=", date("Y-m-d H:i:s"))
            ->select("id")
            ->first();
    }

    private function generate()
    {
        return md5(date('y-m-d h:i:s'));
    }

}
