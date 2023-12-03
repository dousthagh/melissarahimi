<?php

namespace App\ViewModel\Lesson\LessonContent;

class SaveContentViewModel
{
    private $id = 0;
    private $lessonId = 0;
    private $content;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLessonId(): int
    {
        return $this->lessonId;
    }

    public function setLessonId(int $lessonId): void
    {
        $this->lessonId = $lessonId;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }


}
