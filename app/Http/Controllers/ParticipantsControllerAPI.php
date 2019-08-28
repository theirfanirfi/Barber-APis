<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant as pt;
class ParticipantsControllerAPI extends Controller
{
    //

    public function getParticipants(){
        $p = new pt();
        $par = $p->getParticipants();
        if(sizeof($par) > 0){
            return response()->json([
                'participants' => $par,
                'isError' => false,
                'isAuthenticated' => true,
                'isFound' => true,
            ]);
        }else {
            return response()->json([
                'participants' => $par,
                'isError' => true,
                'isFound' => false,
                'isAuthenticated' => true,
            ]);
        }

    }
}
