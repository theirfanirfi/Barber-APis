<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;
class Messenger extends Model
{
    //
    protected $table = "messenger";

    public function getMessages($pid){
    	$messages = Messenger::where(['p_id' => $pid]);

    	return $messages->orderby('m_id','ASC');
    }

    public function checkParticipants($admin_id,$user_id){
    	$part = Participant::where(['admin_id' => $admin_id,'user_id' => $user_id]);
    	if($part->count() > 0){
    		return $part->first();
    	}else {
    		return false;
    	}
	}

	public function getLastMessage($pid){
        $chats = Messenger::where(['pid' => $pid]);
        return $chats;
    }

    public function getUnReadMessagesCount($chat_id){
        $chats = Messenger::where(['p_id' => $chat_id, 'isRead' => '0'])->count();
        return $chats;
    }
}
