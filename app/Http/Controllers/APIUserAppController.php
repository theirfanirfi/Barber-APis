<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
class APIUserAppController extends Controller
{
    //

    public function getGallery(){
                $gallery = Gallery::orderBy('id','DESC');
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
    }
}
