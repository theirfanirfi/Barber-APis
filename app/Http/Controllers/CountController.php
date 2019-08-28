<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Messenger;
class CountController extends Controller
{
    //

    public function getCountForNotificationsAndChat(){
        $aps = Appointment::where(['is_confirmed' => 0])->count();
        $msn = Messenger::where(['is_read' => 0])->count();

        return response()->json([
            'isError' => false,
            'isAuthenticated' => true,
            'chat_count' => $msn,
            'appointments_count' => $aps,
        ]);
    }
}
