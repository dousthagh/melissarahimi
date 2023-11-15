<?php

namespace App\ViewModel\Message;

class SendMessageViewModel
{
    private $senderUserId;
    private $receivedUserId;
    private $title;
    private $content;
    private $link;
    private $type;
    private $parentId;

    /**
     * @return mixed
     */
    public function getSenderUserId()
    {
        return $this->senderUserId;
    }

    /**
     * @param mixed $senderUserId
     */
    public function setSenderUserId($senderUserId): void
    {
        $this->senderUserId = $senderUserId;
    }

    /**
     * @return mixed
     */
    public function getReceivedUserId()
    {
        return $this->receivedUserId;
    }

    /**
     * @param mixed $receivedUserId
     */
    public function setReceivedUserId($receivedUserId): void
    {
        $this->receivedUserId = $receivedUserId;
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

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
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
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }



}
