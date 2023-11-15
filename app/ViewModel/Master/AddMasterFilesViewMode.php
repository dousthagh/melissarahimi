<?php

namespace App\ViewModel\Master;

class AddMasterFilesViewMode
{
    private $userLevelCategoryId;
    private $file;

    /**
     * @return mixed
     */
    public function getUserLevelCategoryId()
    {
        return $this->userLevelCategoryId;
    }

    /**
     * @param mixed $userLevelCategoryId
     */
    public function setUserLevelCategoryId($userLevelCategoryId): void
    {
        $this->userLevelCategoryId = $userLevelCategoryId;
    }



    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }


}
