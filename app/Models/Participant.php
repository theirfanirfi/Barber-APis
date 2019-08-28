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

    // 	$participants = DB::table('participants')

    //     ->leftjoin('users',['users.id' => 'participants.user_id'])

    //     //->leftjoin('messenger',['p_id' => 'participants.id'])
    //     //->leftjoin('messages',['messages.chat_id' => 'participants.chat_id'])

    //     ->select('participants.*','users.name as name','users.profile_image'
    //     // ,
    //     )
    //    // DB::raw("count(*) as read"))
    //     // ->groupBy('participants.id')
    //     // ->leftjoin('messenger',function($query){
    //     //     $query->on(['p_id' => 'participants.id']);
    //     // })
    //     ;
    //    // ->groupby('participants.sender_id');
    //    //,DB::raw("IF(users.user_id = '".$user_id."',true,false) as amIuserOne"));

    //     return $participants;


    $participants = DB::select("SELECT participants.*,
    users.name,
    profile_image,
    (select count(*) from messenger where is_read =0 AND messenger.p_id = participants.id) as count,
    (select CONCAT(substr(msg,1,25),'...') from messenger where messenger.p_id = participants.id order by messenger.id DESC limit 1) as last_msg
    FROM participants LEFT JOIN users on users.id = participants.user_id
    LEFT JOIN messenger on messenger.p_id = participants.id
    group by participants.id order by count DESC", [1]);
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
