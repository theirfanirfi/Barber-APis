<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //

    public function profilePictureUpdate(Request $req){
        $file = $req->file('image');
    //    $base64_string = $req->input('image');
    //    $output_file = "./baseimage/img".rand(1,10000).".jpg";
    //    file_put_contents($output_file, file_get_contents($base64_string));
    if($req->hasFile('image')){
        $file->move("./uploads",$file->getClientOriginalName());
        return response()->json([
            //  'message' => $_FILES['image']['name'],
             'message' => $file->getClientOriginalName(),
            // 'uri' => $_FILES['image']['path'],
            // //'no' => $_FILES['image']['path'],
             'rand' => rand(1,1000),
             'w' => 'moved'
         ]);
    }
        return response()->json([
           //  'message' => $_FILES['image']['name'],
            //'message' => $file->getClientOriginalName(),
        //    'uri' => $_FILES['image']['path'],
        //    //'no' => $_FILES['image']['path'],
            'rand' => rand(1,1000),
            'w' => 'no'
        ]);
    }
}
