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

    	return $messages->orderby('id','ASC');
    }

    public function checkParticipants($admin_id,$user_id){
    	$part = Participant::where(['admin_id' => $admin_id,'user_id' => $user_id]);
    	if($part->count() > 0){
    		return $part->first();
    	}else {
    		return false;
    	}
    }

    public function checkParticipantsForFrontEndApp($user_id){
    	$part = Participant::where(['user_id' => $user_id]);
    	if($part->count() > 0){
    		return $part->first();
    	}else {
    		return false;
    	}
	}

	public function getLastMessage($pid){
        $chats = Messenger::where(['p_id' => $pid]);
        return $chats;
    }

    public function getUnReadMessagesCount($chat_id){
        $chats = Messenger::where(['p_id' => $chat_id, 'isRead' => '0'])->count();
        return $chats;
    }

    public static function getMessagesForUser($user_id){
        $messages = Participant::where(['user_id' => $user_id])
        ->leftjoin('messenger',['messenger.p_id' => 'participants.id'])
        ->leftjoin('users',['users.id' => 'participants.admin_id'])
        ->select('messenger.*','messenger.created_at as msg_send_time','participants.*','users.name as admin_name','users.profile_image as admin_profile_image');
        return $messages;
    }
}
