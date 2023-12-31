<?php

namespace App\Services\Video;

use Exception;

class VideoService
{
    public function generateVideoAddress($videoId){
        $videoConfig = $this->getSpecificVideo($videoId);
        return $videoConfig->data->player_url;
    }
    public function getSpecificVideo($id){
        try{
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://napi.arvancloud.ir/vod/2.0/videos/'.$id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: apikey '.$this->getApiKey()
                ),
            ));
    
            $response = curl_exec($curl);
    
            curl_close($curl);
            return json_decode($response);
    
        }catch(Exception $ex){
            return $this->getSpecificVideo($id);
        }
        
    }

    private function getApiKey(){
        return env('VIDEO_API_KEY');
    }
}
