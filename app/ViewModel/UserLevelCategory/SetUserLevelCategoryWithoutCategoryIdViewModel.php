<?php

namespace App\ViewModel\UserLevelCategory;

class SetUserLevelCategoryWithoutCategoryIdViewModel
{
    private $parentUserLevelCategoryId;
    private $userId;
    private $newLevelKey;

    /**
     * @return mixed
     */
    public function getParentUserLevelCategoryId()
    {
        return $this->parentUserLevelCategoryId;
    }

    /**
     * @param mixed $parentUserLevelCategoryId
     */
    public function setParentUserLevelCategoryId($parentUserLevelCategoryId): void
    {
        $this->parentUserLevelCategoryId = $parentUserLevelCategoryId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getNewLevelKey()
    {
        return $this->newLevelKey;
    }

    /**
     * @param mixed $newLevelKey
     */
    public function setNewLevelKey($newLevelKey): void
    {
        $this->newLevelKey = $newLevelKey;
    }


}
