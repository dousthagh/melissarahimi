<?php

namespace App\ViewModel\Course;

class SaveCourseViewModel
{
    private $id = 0;
    private $levelCategoryId = 0;
    private $title;
    private $description;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLevelCategoryId()
    {
        return $this->levelCategoryId;
    }

    /**
     * @param mixed $levelCategoryId
     */
    public function setLevelCategoryId($levelCategoryId): void
    {
        $this->levelCategoryId = $levelCategoryId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

}
