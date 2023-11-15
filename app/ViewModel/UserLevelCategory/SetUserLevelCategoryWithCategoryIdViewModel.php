<?php

namespace App\ViewModel\UserLevelCategory;

class SetUserLevelCategoryWithCategoryIdViewModel
{
    private $userId;
    private $categoryId;
    private $parentId;
    private $expireDate;
    private $startUserLevelCategoryId;


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
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * @param mixed $expireDate
     */
    public function setExpireDate($expireDate): void
    {
        $this->expireDate = $expireDate;
    }

    /**
     * @return mixed
     */
    public function getStartUserLevelCategoryId()
    {
        return $this->startUserLevelCategoryId;
    }

    /**
     * @param mixed $startUserLevelCategoryId
     */
    public function setStartUserLevelCategoryId($startUserLevelCategoryId): void
    {
        $this->startUserLevelCategoryId = $startUserLevelCategoryId;
    }
}
