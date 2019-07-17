<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\User;
class APIAdminGalleryController extends Controller
{
    //

    public function uploadimage(Request $req){
        $title = $req->input('image_title');
        $token = $req->input('token');
        if(empty($title) || empty($token)){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'message' => "Arguments must be provided. error "
            ]);
        }else {
            if($req->hasFile('image')){
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $image_name = time().rand(0,10000).".".$extension;
                $path = "./uploads/gallery/";
                $user = User::getBarberByToken($token);
                if($file->move($path,$image_name)){

                    $g = new Gallery();
                    $g->image_name = $image_name;
                    $g->image_title = $title;
                    $g->user_id = $user->id;

                    if($g->save()){
                        return response()->json([
                            'isAuthenticated' => true,
                            'isError' => false,
                            'isSaved' => true,
                            'message' => "Image Uploaded."
                        ]);
                    }else {
                        return response()->json([
                            'isAuthenticated' => true,
                            'isError' => true,
                            'message' => "Error occurred in saving the uploaded image. Please try again."
                        ]);
                    }
                }else {
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => true,
                        'message' => "Error occurred in uploading the image. Please try again."
                    ]);
                }

            }else {
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => true,
                    'message' => "Product Image must be provided. "
                ]);
            }
        }
    }

    public function getGallery(Request $req){
        $token = $req->input('token');
        if(empty($token)){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'message' => "Arguments must be provided. error "
            ]);
        }else {
            $user = User::getBarberByToken($token);
            if($user){
                $gallery = Gallery::where(['user_id' => $user->id]);
                if($gallery->count() > 0){
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => false,
                        'isFound' => true,
                        'gallery' => $gallery->get(),
                        'message' => "Image Uploaded."
                    ]);
                }else {
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => true,
                        'isFound' => false,
                        'message' => "Your gallery is empty."
                    ]);
                }
            }else {
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => true,
                    'message' => "Unable to check your details."
                ]);
            }
        }
    }
}
