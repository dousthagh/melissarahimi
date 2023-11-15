<?php

namespace App\ViewModel\Lesson;

class NewSampleWorkViewModel
{
    private $lessonId;
    private $userLevelCategoryId;
    private $file;
    private $description;

    /**
     * @return mixed
     */
    public function getLessonId()
    {
        return $this->lessonId;
    }

    /**
     * @param mixed $lessonId
     */
    public function setLessonId($lessonId): void
    {
        $this->lessonId = $lessonId;
    }

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
