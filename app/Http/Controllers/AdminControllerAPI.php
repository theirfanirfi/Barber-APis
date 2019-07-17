<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category as Cat;
use App\Product as Pd;
use App\Checkout as CK;
use App\Order;
use App\User;
use App\Mail\OrderShipped as OPM;
use Mail;
use Illuminate\Support\Facades\Hash;
class AdminControllerAPI extends Controller
{
    //

    public function addCategory(Request $req){
        $title = $req->input('cat_title');
        if($title != ""){
        if($req->hasFile('image')){
            $file = $req->file('image');
            $image_name = $file->getClientOriginalName();
            $path = "./uploads/categories/";
            $cat = new Cat();
            $cat->cat_title = $title;
            $cat->cat_image = "http://192.168.10.4/Ecommerce/public/uploads/categories/".$title.time();
            if($file->move($path,$image_name)){

                if($cat->save()){
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => false,
                        'isImageError' => false,
                        'isSaved' => true,
                        'isUploaded' => true,
                        'message' => "Category Added."
                    ]);
                }else {
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => false,
                        'isImageError' => false,
                        'isSaved' => false,
                        'isUploaded' => true,
                        'message' => "Image Uploaded but not saved. Try again."
                    ]);
                }
            }else {
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => true,
                    'isImageError' => true,
                    'isSaved' => false,
                    'isUploaded' => false,
                    'message' => "Error occurred in uploading the category image"
                ]);
            }
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isImageError' => true,
                'message' => "Image must be provided."
            ]);
        }
    }else {
        return response()->json([
            'isAuthenticated' => true,
            'isError' => true,
            'message' => "Category title must be provided."
        ]);
    }
    }


    public function getCategories(Request $req){
        $cats = Cat::all();
        if($cats->count() > 0){
            return response()->json([
                'isError' => false,
                'isAuthenticated' => true,
                'isFound' => true,
                'cats' => $cats
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isAuthenticated' => true,
                'isFound' => false
            ]);
        }
    }

    public function getProducts(Request $req){
        $cat_id = $req->input('cat_id');
        if(empty($cat_id) || !is_numeric($cat_id) || $cat_id == null){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isFound' => false,
                'message' => 'Category Id must be provided.'
            ]);
        }else {
        $products = Pd::getProductsByCat($cat_id);
        if($products->count() > 0){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => false,
                'isFound' => true,
                'products' => $products->get()
            ]);
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isFound' => false,
                'message' => 'No products found'
            ]);
        }
    }
    }

    public function addproduct(Request $req){
        $name = $req->input('product_name');
        $quantity = $req->input('product_quantity');
        $price = $req->input('product_price');
        $cat_id = $req->input('cat_id');
        $product_description = $req->input('desc');

        if(empty($name) || empty($quantity) ||empty($cat_id) || empty($product_description)){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'message' => "Arguments must be provided."
            ]);
        }else {
            if($req->hasFile('image')){
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $product_image_name = time().$quantity.$cat_id.rand(0,10000).".".$extension;
                $path = "./uploads/products/";

                if($file->move($path,$product_image_name)){
                    $product = new Pd();
                    $product->product_name = $name;
                    $product->quantity = $quantity;
                    $product->available = $quantity;
                    $product->sold = 0;
                    $product->product_desc = $product_description;
                    $product->product_price = $price;
                    $product->cat_id = $cat_id;
                    $product->product_image = "http://192.168.10.4/Ecommerce/public/uploads/products/".$product_image_name;

                    if($product->save()){
                        return response()->json([
                            'isAuthenticated' => true,
                            'isError' => false,
                            'isSaved' => true,
                            'message' => "Product Added."
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
                    'message' => "Product Image must be provided."
                ]);
            }
        }
    }

    public function getProduct(Request $req){
        $product_id = $req->input('product_id');
        $product = Pd::where(['product_id' => $product_id]);
        if($product->count() > 0){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => false,
                'isFound' => true,
                'product' => $product->first()
            ]);
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isFound' => false,
                'message' => "No such Product exists in the system."
            ]);
        }
    }

    public function deleteproduct(Request $req){
        $product_id = $req->input('product_id');

        $product = Pd::where(['product_id' => $product_id]);
        if($product->count() > 0){

            if($product->first()->delete()){
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => false,
                    'isFound' => true,
                    'isDeleted' => true,
                    'message' => 'product deleted.'
                ]);
            }else {
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => true,
                    'isFound' => true,
                    'isDeleted' => false,
                    'message' => 'Error occurred in deleting the product. Try again.'
                ]);
            }


        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isFound' => false,
                'message' => "No such Product exists in the system."
            ]);
        }
    }

    public function getNewOrders(Request $req){
        $ck = CK::where(['is_processed' => 0,'is_paid' => 1]);
       // $checkouts = $ck->getNewOrders();
       if($ck->count() > 0){
        return response()->json([
            'orders' => $ck->get(),
            'isFound' => true,
            'isError' => false,
            'isAuthenticated' => true,
        ]);
       }else {
        return response()->json([
            'isFound' => false,
            'isError' => false,
            'isAuthenticated' => true,
        ]);
       }
    }


    public function getOlderOrders(Request $req){
        $ck = CK::where(['is_processed' => 1,'is_paid' => 1]);
       // $checkouts = $ck->getNewOrders();
       if($ck->count() > 0){
        return response()->json([
            'orders' => $ck->get(),
            'isFound' => true,
            'isError' => false,
            'isAuthenticated' => true,
            'message' => 'Loading...'
        ]);
       }else {
        return response()->json([
            'isFound' => false,
            'isError' => false,
            'isAuthenticated' => true,
            'message' => 'No orders found.'
        ]);
       }
    }

    public function getcheckout(Request $req){
        $checkout_id = $req->input('checkout_id');
        if(empty($checkout_id) || !is_numeric($checkout_id) || $checkout_id == null){
            return response()->json([
                'isFound' => false,
                'isError' => true,
                'isAuthenticated' => true,
                'message' => 'Arguments must be provided.'
            ]);
        }else {
            $ck = CK::where(['id' => $checkout_id]);
            if($ck->count() > 0){
                return response()->json([
                    'isFound' => true,
                    'isError' => false,
                    'isAuthenticated' => true,
                    'order' => $ck->first(),
                    'message' => 'Loading'
                ]);
            }else {
                return response()->json([
                    'isFound' => false,
                    'isError' => true,
                    'isAuthenticated' => true,
                    'message' => 'No such order exists.'
                ]);
            }
        }
    }

    public function getOrderProducts(Request $req){
        $checkout_id = $req->input('checkout_id');
        if(empty($checkout_id) || !is_numeric($checkout_id) || $checkout_id == null){
            return response()->json([
                'isFound' => false,
                'isError' => true,
                'isAuthenticated' => true,
                'message' => 'Arguments must be provided.'
            ]);
        }else {
            $ck = CK::where(['id' => $checkout_id]);
            if($ck->count() > 0){
                $orders = Order::getOrderProducts($checkout_id);
                if($orders->count() > 0){

                return response()->json([
                    'isFound' => true,
                    'isError' => false,
                    'isAuthenticated' => true,
                    'order' => $ck->first(),
                    'orders' => $orders->get(),
                    'message' => 'Loading'
                ]);
                }else {

                }
            }else {
                return response()->json([
                    'isFound' => false,
                    'isError' => true,
                    'isAuthenticated' => true,
                    'message' => 'No such order exists.'
                ]);
            }
        }

    }

    public function shipOrder(Request $req){
        $checkout_id = $req->input('checkout_id');
        if(empty($checkout_id) || !is_numeric($checkout_id) || $checkout_id == null){
            return response()->json([
                'isShipped' => false,
                'isError' => true,
                'isAuthenticated' => true,
                'message' => 'Arguments must be provided.'
            ]);
        }else {
            $ck = CK::where(['id' => $checkout_id, 'is_processed' => 0]);
            if($ck->count() > 0){
                $ck = $ck->first();
                $ck->is_processed = 1;
                if($ck->save()){

                return response()->json([
                    'isShipped' => true,
                    'isError' => false,
                    'isAuthenticated' => true,
                    'message' => 'The order is shipped.'
                ]);
                }else {
                    return response()->json([
                        'isShipped' => false,
                        'isError' => true,
                        'isAuthenticated' => true,
                        'message' => 'Error occurred in shipping the order. Try again.'
                    ]);
                }
            }else {
                return response()->json([
                    'isShipped' => false,
                    'isError' => true,
                    'isAuthenticated' => true,
                    'message' => 'No such order exists.'
                ]);
            }
        }
    }


    public function updateProduct(Request $req){
        $name = $req->input('product_name');
        $quantity = $req->input('product_quantity');
        $price = $req->input('product_price');
        $cat_id = $req->input('cat_id');
        $pid = $req->input('product_id');
        $product_description = $req->input('desc');

        if(empty($name) || empty($quantity) ||empty($cat_id) || empty($pid) || empty($product_description)){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'message' => "Arguments must be provided."
            ]);
        }else {
            if($req->hasFile('image')){
                $file = $req->file('image');
                $extension = $file->getClientOriginalExtension();
                $product_image_name = time().$quantity.$cat_id.rand(0,10000).".".$extension;
                $path = "./uploads/products/";

                if($file->move($path,$product_image_name)){
                    $product = Pd::find($pid);
                    $product->product_name = $name;
                    $product->quantity = $product->available + $quantity;
                    $product->available = $product->available + $quantity;
                    $product->product_desc = $product_description;
                    $product->product_price = $price;
                    $product->cat_id = $cat_id;
                    $product->product_image = "http://192.168.10.4/Ecommerce/public/uploads/products/".$product_image_name;

                    if($product->save()){
                        return response()->json([
                            'isAuthenticated' => true,
                            'isError' => false,
                            'isSaved' => true,
                            'message' => "Product Updated."
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
                // return response()->json([
                //     'isAuthenticated' => true,
                //     'isError' => true,
                //     'message' => "Product Image must be provided."
                // ]);


                $product = Pd::find($pid);
                $product->product_name = $name;
                $product->quantity = $product->available + $quantity;
                $product->available = $product->available + $quantity;
              //  $product->sold = 0;
              $product->product_desc = $product_description;
                $product->product_price = $price;
                $product->cat_id = $cat_id;
               // $product->product_image = "http://192.168.10.4/Ecommerce/public/uploads/products/".$product_image_name;

                if($product->save()){
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => false,
                        'isSaved' => true,
                        'message' => "Product Updated."
                    ]);
                }else {
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => true,
                        'message' => "Error occurred in saving the uploaded image. Please try again."
                    ]);
                }
            }
        }
    }

    public function getmembers(Request $req){
        $members = User::getMembers();
        if($members->count() > 0){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => false,
                'isFound' => true,
                'members' => $members->get(),
                'message' => "Loading"
            ]);
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isFound' => false,
                'message' => "No Member found."
            ]);
        }
    }

    public function getMemberCheckouts(Request $req){
        $mem_id = $req->input('mem_id');
        if(empty($mem_id) || !is_numeric($mem_id) || $mem_id == null){
            return response()->json([
                'isShipped' => false,
                'isError' => true,
                'isAuthenticated' => true,
                'message' => 'Arguments must be provided.'
            ]);
        }else {
            $user = User::where(['id' => $mem_id,'role' => 0]);
            if($user->count() > 0){
        $ck = CK::where(['user_id' => $mem_id]);
        if($ck->count() > 0){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => false,
                'isFound' => true,
                'checkouts' => $ck->get(),
                'message' => "Loading"
            ]);
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isFound' => false,
                'message' => "No Checkout for this user found."
            ]);
        }
    }else {
        return response()->json([
            'isAuthenticated' => true,
            'isError' => true,
            'isFound' => false,
            'message' => "No such member exists in the system."
        ]);
    }
}
    }

    public function getProfile(Request $req){
        $token = $req->input('token');
        $profile = User::getProfile($token);
        if($profile->count() > 0){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => false,
                'isFound' => true,
                'user' => $profile->first(),
                'message' => "Loading..."
            ]);
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isFound' => false,
                'message' => "No profile found."
            ]);
        }
    }

    public function updateProfile(Request $req){
        $token = $req->input('token');
        $email = $req->input('email');
        $name = $req->input('name');
        $duration = $req->input('sduration');
        $updatePassword = $req->input('uc');
        if(empty($email) || empty($name) || empty($duration) || !is_numeric($duration) ){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isUpdated' => false,
                'message' => "Arguments must be provided."
            ]);
        }else {

            $profile = User::getProfile($token);


            if($profile->count() > 0){
                $pf = $profile->first();
                $pf->name = $name;
                $checkEmail = User::where(['email' => $email])->where('id','!=',$pf->id)->count();
                if($checkEmail == 0){
                $pf->email = $email;
                }else {
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => true,
                        'isUpdated' => false,
                        'message' => "The entered email is already taken. Please use another one."
                    ]);
                }
                $pf->shipmentduration = $duration;

            if($updatePassword == 1){
                $cpass = $req->input('cpass');
                $npass = $req->input('npass');
                if(empty($cpass) || empty($npass)){
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => true,
                        'isUpdated' => false,
                        'message' => "Password fields cannot be empty."
                    ]);
                }else if(strlen($npass) < 6) {
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => true,
                        'isUpdated' => false,
                        'message' => "Password length must be at least six characters long."
                    ]);
                }else {
                    if(Hash::check($cpass, $pf->password)){
                        $pf->password = Hash::make($npass);
                    }else {
                        return response()->json([
                            'isAuthenticated' => true,
                            'isError' => true,
                            'isUpdated' => false,
                            'message' => "Current Password is invalid."
                        ]);
                    }
                }
            }


            if($pf->save()){
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => false,
                    'isUpdated' => true,
                    'user' => $pf,
                    'message' => "Profile updated."
                ]);
            }else {
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => false,
                    'isUpdated' => false,
                    'message' => "Error occurred in saving the updation. Please try again."
                ]);
            }


            }else {
                return response()->json([
                    'isAuthenticated' => true,
                    'isError' => true,
                    'isFound' => false,
                    'message' => "No profile found."
                ]);
            }


        }


    }

    // public function sendEmail(){
    //     $data['title'] = 'working';
    //     Mail::to('theirfi@gmail.com')->send(new OPM($data));
    // }


    public function sendEmail()
    {
        $data['title'] = "This is Test Mail Tuts Make";

        Mail::send('Mail.ordershipped', $data, function($message) {

            $message->to('tutsmake@gmail.com', 'Receiver Name')

                    ->subject('Tuts Make Mail');
        });

        if (Mail::failures()) {
           return response()->Fail('Sorry! Please try again latter');
         }else{
           return response()->success('Great! Successfully send in your mail');
         }
    }


}
