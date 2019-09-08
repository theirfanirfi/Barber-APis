<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Messenger;
use App\User;
class MessengerControllerAPI extends Controller
{
    //

    public function sendMessage(Request $req){
    	$token = $req->input('token');
		 $TO_CHAT_WITH = $req->input('id');
		 $message = $req->input('msg');

		 if($TO_CHAT_WITH == null || $message == null){
     return response()->json([
                'isAuthenticated' => false,
                'isEmpty' => true,
                'isError' => true,
                'message' => 'Arguments must be provided.'
            ]);
    }else {
        $user = User::where(['token' => $token]);

        if(!($user->count() > 0)){
            return response()->json([
                'isAuthenticated' => false,
                'isEmpty' => false,
                'isError' => true,
                'message' => 'Not authenticated'
            ]);
        }
        else
        {
        //here the sending goes.
        $user = $user->first();
        	$msg = new Messenger();
                // date_default_timezone_set("Asia/Karachi");
            $timezone = date_default_timezone_get();
            date_default_timezone_set($timezone);
        	$msg->sender_id = $user->id;
        	$msg->reciever_id = $TO_CHAT_WITH;
            $msg->msg = $message;

        	//check if the participants table has entry for the chat or not.
            //has the user chatted before?
            $p = $msg->checkParticipants($user->id,$TO_CHAT_WITH);
    		if($p){
    				$msg->p_id = $p->id;
    		}else {
    			$p = new Participant();
    			$p->admin_id = $user->id;
    			$p->user_id = $TO_CHAT_WITH;

    			if($p->save()){
    				$msg->p_id = $p->id;
    			}
    		}

    		if($msg->save()){
    			 return response()->json([
                'isAuthenticated' => true,
                'isEmpty' => false,
                'isError' => false,
                'isSent' => true,
                'message' => 'Message sent.'
            	]);

    		}else {

    			 return response()->json([
                'isAuthenticated' => true,
                'isEmpty' => false,
                'isError' => true,
                'isSent' => false,
                'message' => 'Error occurred in sending the message.'
            	]);
    		}

        }
    }
    }

	public function getMessages(Request $req){
		 $token = $req->input('token');
		 $pid = $req->input('pid');
		 if($token != null && $pid != null){

        	$m = new Messenger();
        	$messages = $m->getMessages($pid);
        	if($messages->count() > 0){

        			return response()->json([
        				'isFound' => true,
        				'isError' => false,
        				'isAuthenticated' => true,
        				'response_message' => 'loading',
        				'messages' => $messages->get()
        			]);

        	}else {

					return response()->json([
        				'isFound' => false,
        				'isError' => false,
        				'isAuthenticated' => true,
        				'response_message' => 'You have not chated with the user yet.'
        			]);
        	}

    }else {
    	  return response()->json([
                'isAuthenticated' => false,
                'isEmpty' => true,
                'isError' => true,
                'response_message' => 'Arguments must be provided.'
            ]);
    }
    }



    public function getUnReadMessageAndCount(Request $req){

        $token = $req->input('token');
        $pid = $req->input('pid');

if($token == "" || $pid == ""){
    return response()->json([
        'isEmpty' => true,
        'isError' => true,
        'isAuthenticated' => false,
        'message' => 'Arugments must be provided.'
    ]);
}else {
    $m = new Messenger();

    $last_message = $m->getLastMessage($pid);
    $count = $m->getUnReadMessagesCount($pid);

    return response()->json([
        'isEmpty' => false,
        'isError' => false,
        'isAuthenticated' => true,
        'last_message_count' => $last_message->count(),
        'last_message' => $last_message->get()->last(),
        'count_unread_messages' => $count,
        'message' => 'loading'
    ]);
}
    }



}
