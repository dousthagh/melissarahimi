<?php

namespace App\ViewModel\Message;

class SendChangeLevelMessageViewModel
{
    private $receiverUserId;
    private $senderUserId;
    private $userName;
    private $levelName;
    private $levelKey;
    private $levelCode;
    private $receiverEmail;

    /**
     * @return mixed
     */
    public function getReceiverEmail()
    {
        return $this->receiverEmail;
    }

    /**
     * @return mixed
     */
    public function getReceiverUserId()
    {
        return $this->receiverUserId;
    }

    /**
     * @param mixed $receiverUserId
     */
    public function setReceiverUserId($receiverUserId): void
    {
        $this->receiverUserId = $receiverUserId;
    }

    /**
     * @param mixed $receiverEmail
     */
    public function setReceiverEmail($receiverEmail): void
    {
        $this->receiverEmail = $receiverEmail;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getLevelName()
    {
        return $this->levelName;
    }

    /**
     * @param mixed $levelName
     */
    public function setLevelName($levelName): void
    {
        $this->levelName = $levelName;
    }

    /**
     * @return mixed
     */
    public function getLevelKey()
    {
        return $this->levelKey;
    }

    /**
     * @param mixed $levelKey
     */
    public function setLevelKey($levelKey): void
    {
        $this->levelKey = $levelKey;
    }

    /**
     * @return mixed
     */
    public function getLevelCode()
    {
        return $this->levelCode;
    }

    /**
     * @param mixed $levelCode
     */
    public function setLevelCode($levelCode): void
    {
        $this->levelCode = $levelCode;
    }

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

}
