<?php

namespace App\ViewModel\Lesson;

class SaveLessonFileViewModel
{
    private $lessonId;
    private $title;
    private $file;

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


}
