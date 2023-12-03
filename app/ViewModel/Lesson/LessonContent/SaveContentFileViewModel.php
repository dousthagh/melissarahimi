<?php

namespace App\ViewModel\Lesson\LessonContent;

class SaveContentFileViewModel
{
    private $lessonContentId;
    private $title;
    private $file;
    private $sortOrder;

    /**
     * @return mixed
     */
    public function getLessonContentId()
    {
        return $this->lessonContentId;
    }

    /**
     * @param mixed $lessonContentId
     */
    public function setLessonContentId($lessonContentId): void
    {
        $this->lessonContentId = $lessonContentId;
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
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     */
    public function setSortOrder($sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

}
