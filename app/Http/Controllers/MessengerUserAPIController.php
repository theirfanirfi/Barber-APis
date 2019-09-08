<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messenger;
use App\User;
class MessengerUserAPIController extends Controller
{
    //

    public function getUserMessagesInFrontendApp(Request $req){
        $token = $req->input('token');
        if($token == null || empty($token)){
            return response()->json([
                'isError' => true,
                'message' => 'Arguments must be provided.'
            ]);
        }else {

            $user = User::where(['token' => $token]);
            if($user->count() > 0){
                $user = $user->first();

                $msgs = Messenger::getMessagesForUser($user->id);

                if($msgs->count() > 0){
                    return response()->json([
                        'isError' => false,
                        'isFound' => true,
                        'messages' => $msgs->get(),
                        'message' => 'Invalid User.'
                    ]);
                }else {
                    return response()->json([
                        'isError' => false,
                        'isFound' => false,
                        'message' => 'You have not initiated chat yet.'
                    ]);
                }
            }else {
                return response()->json([
                    'isError' => true,
                    'message' => 'Invalid User.'
                ]);
            }



        }
    }


    public function sendMessage(Request $req){
    	$token = $req->input('token');
		 $message = $req->input('msg');

		 if(empty($message) || $message == null){
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
            $msg->msg = $message;

        	//check if the participants table has entry for the chat or not.
            //has the user chatted before?
            $p = $msg->checkParticipantsForFrontEndApp($user->id);
    		if($p){
                    $msg->p_id = $p->id;
                    $msg->reciever_id = $p->admin_id;
    		}else {
                $admin = User::where(['role' => 1,'is_barber' => 1])-get()->first();
    			$p = new Participant();
    			$p->admin_id = $admin->id;
    			$p->user_id = $user->id;

    			if($p->save()){
                    $msg->p_id = $p->id;
                    $msg->reciever_id = $admin->id;

    			}
    		}

    		if($msg->save()){
                $m = Messenger::find($msg->id);
    			 return response()->json([
                'isAuthenticated' => true,
                'isEmpty' => false,
                'isError' => false,
                'isSent' => true,
                'msg' => $m,
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

    public function getChatCount(Request $req){
        $token = $req->input('token');
       $user = User::where(['token' => $token]);
       if($user->count() > 0 ){

       }else {
           //
       }
    }
}
