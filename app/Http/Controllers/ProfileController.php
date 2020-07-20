<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    //

    public function profilePictureUpdate(Request $req)
    {
        $file = $req->file('image');
        $token = $req->input('token');
                return response()->json([
                    'isError' => true,
                    'message' => $req->input('tk')
                ]);
        $user = User::where(['token' => $token]);
        if ($user->count() > 0) {
            $user = $user->get()->first();
            if ($req->hasFile('image')) {
                $image_name = $file->getClientOriginalName();
                if ($file->move("./uploads/profile/", $image_name)) {
                    $user->profile_image = $image_name;
                    if ($user->save()) {
                        return response()->json([
                            'isChanged' => true,
                            'isError' => false,
                            'user' => $user,
                            'message' => 'Profile image changed.'
                        ]);
                    } else {
                        return response()->json([
                            'isError' => true,
                            'message' => 'Error occurred in saving the image. Try again.'
                        ]);
                    }
                } else {
                    return response()->json([
                        'isError' => true,
                        'message' => 'Error occurred in uploading the image. Try again.'
                    ]);
                }
            } else {
                return response()->json([
                    'isError' => true,
                    'message' => 'Image must be provided.'
                ]);
            }
        } else {
            return response()->json([
                'isError' => true,
                'message' => 'Invalid user provided.'
            ]);
        }
    }
}
