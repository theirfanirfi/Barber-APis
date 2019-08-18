<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant as pt;
class ParticipantsControllerAPI extends Controller
{
    //

    public function getParticipants(){
        $p = new pt();
        $par = $p->getParticipants()->get();
        return response()->json([
            'participants' => $par,
            'isError' => false,
            'isFound' => true,
        ]);
    }
}
