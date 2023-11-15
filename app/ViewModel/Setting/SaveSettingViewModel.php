<?php

namespace App\ViewModel\Setting;

class SaveSettingViewModel
{
    private $logo;
    private $sideImage;

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getSideImage()
    {
        return $this->sideImage;
    }

    /**
     * @param mixed $sideImage
     */
    public function setSideImage($sideImage): void
    {
        $this->sideImage = $sideImage;
    }

}
