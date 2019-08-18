<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Participant extends Model
{
    //

    protected $table = "participants";
    protected $primaryKey = "p_id";

    public function getParticipants(){

    	$participants = DB::table('participants')

        ->leftjoin('users',['users.id' => 'participants.user_id'])
       // ->leftjoin('messenger',['p_id' => 'participants.id'])
        //->leftjoin('messages',['messages.chat_id' => 'participants.chat_id'])

        ->select('participants.*','users.name as name','users.profile_image')
        ;
       // ->groupby('participants.sender_id');
       //,DB::raw("IF(users.user_id = '".$user_id."',true,false) as amIuserOne"));

        return $participants;

    }

    public function getLastUnReadMessage($chat_id){
        $chats = Participants::where(['p_id' => $chat_id])->last();
        return $chats;
    }

    public function getUnReadMessagesCount($chat_id){
        $chats = Participants::where(['p_id' => $chat_id, 'isRead' => '0'])->count();
        return $chats;
    }
}
