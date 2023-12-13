<?php

namespace App\ViewModel\Lesson;

class SaveLessonViewModel
{
    private $id = 0;
    private $title;
    private $description;
    private $withSampleWork;

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

    /**
     * @return mixed
     */
    public function getWithSampleWork() : bool
    {
        return $this->withSampleWork;
    }

    /**
     * @param mixed $withSampleWork
     */
    public function setWithSampleWork($withSampleWork): void
    {
        $this->withSampleWork = $withSampleWork;
    }


}
