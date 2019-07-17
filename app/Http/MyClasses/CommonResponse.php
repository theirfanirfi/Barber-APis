<?php

namespace App\Http\MyClasses;
use App\User;

class CommonResponse {

    public static function returnEmpty(){
        return response()->json([
            'isEmpty' => true,
        ]);
    }

    public static function notAuthenticated(){
        return response()->json([
            'isAuthenticated' => false,
        ]);
    }

    public static function invalidCreds(){
        return response()->json([
            'invalidCreds' => true,
        ]);
    }
}
