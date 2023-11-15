<?php

namespace App\Http\Controllers;

use App\Services\Panel\Message\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public MessageService $messageService;
    public function __construct()
    {
        $this->messageService = new MessageService();
    }

    public function GetMessageDetails($messageId){
        $this->messageService->readMessage($messageId);
        $data['details'] = $this->messageService->getMessageDetails($messageId);
        return view('panel.message.message_details', $data);
    }
}
