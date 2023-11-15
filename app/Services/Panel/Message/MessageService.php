<?php

namespace App\Services\Panel\Message;

use App\Mail\ReduceLevelMail;
use App\Mail\WelcomeToNewLevelMail;
use App\Models\LevelCategory;
use App\Models\Message;
use App\Models\UserLevelCategory;
use App\ViewModel\Message\SendMessageViewModel;
use App\ViewModel\Message\SendChangeLevelMessageViewModel;
use Illuminate\Support\Facades\Mail;

class MessageService
{
    public function sendInnerNotification(SendMessageViewModel $model): void
    {
        $message = new Message();
        $message->sender_user_id = $model->getSenderUserId();
        $message->receiver_user_id = $model->getReceivedUserId();
        $message->title = $model->getTitle();
        $message->content = $model->getContent();
        $message->type = $model->getType();
        $message->parent_id = $model->getParentId();
        $message->link = $model->getLink();
        $message->save();
    }

    public function getMessageDetails($messageId)
    {
        $userId = auth()->id();
        $result = Message::whereRaw("(sender_user_id=$userId or receiver_user_id=$userId)")
            ->join("users", "users.id", "=", "sender_user_id")
            ->where("messages.id", $messageId)
            ->select([
                "messages.*",
                "users.name as user_name",
                "users.email as user_email"
            ])
            ->first();
        if (!$result)
            abort(403);

        return $result;
    }

    public function readMessage($messageId)
    {
        Message::where("id", $messageId)
            ->where("receiver_user_id", auth()->id())
            ->update(["is_read" => true]);
    }

    public function sendWelcomeToNewLevelMessage(SendChangeLevelMessageViewModel $model)
    {
        $userLevelCategory = UserLevelCategory::where("code", $model->getLevelCode())
            ->first();
        $data['name'] = $model->getUserName();
        $data['levelName'] = $model->getLevelName();
        $data['logoAddress'] = route('super_admin.level_category.logo', ['level_category_id'=>$userLevelCategory->level_category_id]);
        $data['code'] = $model->getLevelCode();
        try{
            Mail::to($model->getReceiverEmail())->send(new WelcomeToNewLevelMail($data));
        }catch (\Exception $ex){}


        $sendMessageViewModel = new SendMessageViewModel();
        $sendMessageViewModel->setLink($data['logoAddress']);
        $sendMessageViewModel->setType("system");
        $sendMessageViewModel->setTitle(__('message.promote_level_title'));
        $sendMessageViewModel->setContent(__('message.promote_level_content')."<br/>".__("message.new_level_title").": ".$model->getLevelName()."<br/>" . __("message.level_code").": " . $model->getLevelCode());
        $sendMessageViewModel->setReceivedUserId($model->getReceiverUserId());
        $sendMessageViewModel->setSenderUserId($model->getSenderUserId());
        $this->sendInnerNotification($sendMessageViewModel);

    }

    public function sendReduceLevelMessage(SendChangeLevelMessageViewModel $model)
    {
        $data['name'] = $model->getUserName();
        $data['levelName'] = $model->getLevelName();
        $data['logoAddress'] = \route('get_level_logo', ["key" => $model->getLevelKey()]);
        $data['code'] = $model->getLevelCode();
        try{
            Mail::to($model->getReceiverEmail())->send(new ReduceLevelMail($data));
        }
        catch (\Exception $ex){}

        $sendMessageViewModel = new SendMessageViewModel();
        $sendMessageViewModel->setLink($data['logoAddress']);
        $sendMessageViewModel->setType("system");
        $sendMessageViewModel->setTitle(__('message.reduce_level_title'));
        $sendMessageViewModel->setContent(__('message.reduce_level_content')."<br/>".__("message.new_level_title").": ".$model->getLevelName()."<br/>" . __("message.level_code").": " . $model->getLevelCode());
        $sendMessageViewModel->setReceivedUserId($model->getReceiverUserId());
        $sendMessageViewModel->setSenderUserId($model->getSenderUserId());
        $this->sendInnerNotification($sendMessageViewModel);
    }
}
