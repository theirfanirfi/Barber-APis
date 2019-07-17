<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\WhishList;
use App\Category as Cat;
use App\Checkout as CK;
use App\Order;
use Auth;
use Illuminate\Support\Facades\Hash;

class FrontendAPIsController extends Controller
{
    //

    public function getProducts(){
        $pro = Product::all();
        if($pro->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'proudcts' => $pro,
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No products found'
             ]);
        }
    }

    public function getLoggedInUserProducts(Request $req){
        $token = $req->input('token');
        $user= User::getUserByToken($token);
        if($user){
            $pro = Product::getLoggedInUserProducts($user->id);
            if($pro->count() > 0){
                return response()->json([
                   'isError' => false,
                   'isFound' => true,
                   'proudcts' => $pro->get(),
                   'message' => 'Loading'
                ]);
            }else {
                return response()->json([
                    'isError' => false,
                    'isFound' => false,
                    'message' => 'No products found'
                 ]);
            }
        }else {
            return response()->json([
                'isError' => true,
                'isFound' => false,
                'message' => 'You are not logged in to perform this action.'
             ]);
        }

    }

    public function product($id){
        if(!is_numeric($id) || empty($id)){
            return response()->json([
                'isError' => true,
                'isFound' => false,
                'message' => 'Product Id must be provided.'
             ]);
        } else {
        $pro = Product::where(['product_id' => $id]);
        if($pro->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'product' => $pro->first(),
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No products found'
             ]);
        }
    }
    }

    public function getcats(){
        $cat = Cat::all();
        if($cat->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'categories' => $cat,
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'Categories not found'
             ]);
        }
    }

    public function getcatproducts($id){
        if(!is_numeric($id) || empty($id)){
            return response()->json([
                'isError' => true,
                'isFound' => false,
                'message' => 'Category must be provided.'
             ]);
        } else {
        $pro = Product::where(['cat_id' => $id]);
        if($pro->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'products' => $pro->get(),
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No products found'
             ]);
        }
    }
    }

    public function loginPost(Request $req){
        $email = $req->input('email');
        $password = $req->input('password');
        if(empty($password) || empty($email)){
            return response()->json([
                'isError' => true,
                'isLoggedIn' => false,
                'message' => 'None of the field can be empty.'
             ]);
        }else {
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            $user = User::where(['email' => $email, 'role' => 0]);
            // $wls = WL::where(['user_id' => $user->id])->count();
            // Session()->put('wl',$wls);
            if($user->count() > 0){
                $user = $user->first();
            $token = Hash::make(time().$user->id.$user->name.time()*4);
            $user->token = $token;
            if($user->save()){
                return response()->json([
                    'isError' => false,
                    'isLoggedIn' => true,
                    'user' => $user,
                    'message' => 'Loginging in....'
                 ]);
            }else {
                return response()->json([
                    'isError' => true,
                    'isLoggedIn' => false,
                    'message' => 'Error occurred. Please try again.'
                 ]);
            }
        }else {
            return response()->json([
                'isError' => true,
                'isLoggedIn' => false,
                'message' => 'Invalid credentials'
             ]);
        }

        }else {
            return response()->json([
                'isError' => true,
                'isLoggedIn' => false,
                'message' => 'Invalid credentials'
             ]);
        }
    }
    }

    public function updateProfileDetails(Request $req){
        $token = $req->input('token');
        $name = $req->input('name');
        $email = $req->input('email');

        if(empty($name) || empty($email) || empty($token)){
            return response()->json([
                'isError' => true,
                'isLoggedIn' => true,
                'message' => 'None of the field can be empty.'
             ]);
        }else {
            $user= User::getUserByToken($token);
            if($user){
                $user->name = $name;

                if($user->email !== $email){
                    $checkEmail = User::where(['email' => $email])->count();
                    if($checkEmail > 0){
                        return response()->json([
                            'isError' => true,
                            'isLoggedIn' => true,
                            'message' => 'The entered email cannot be assigned to you. It is already in use.'
                         ]);
                    }else {
                        $user->email = $email;
                    }
                }


                if($user->save()){
                    return response()->json([
                        'isError' => false,
                        'isLoggedIn' => true,
                        'isUpdated' => true,
                        'user'=> $user,
                        'message' => 'Details updated.'
                     ]);
                }else {
                    return response()->json([
                        'isError' => true,
                        'isLoggedIn' => true,
                        'message' => 'Error occurred in updating profile details. Please try again.'
                     ]);
                }
            }else {
                return response()->json([
                    'isError' => true,
                    'isLoggedIn' => false,
                    'message' => 'Invalid credentials'
                 ]);
            }
        }

    }


    public function getuser(Request $req){
        $token = $req->input('token');

        if($token == null || empty($token)){
            return response()->json([
                'isError' => true,
                'isLoggedIn' => true,
                'message' => 'User must be provided.'
             ]);
        }else {
            $user= User::getUserByToken($token);
            if($user){
                return response()->json([
                    'isError' => false,
                    'isLoggedIn' => true,
                    'isFound' => true,
                    'user' => $user,
                    'message' => 'Found'
                 ]);
            }else {
                return response()->json([
                    'isError' => true,
                    'isLoggedIn' => true,
                    'isFound' => false,
                    'message' => 'No user Found'
                 ]);
            }
        }
    }

    public function changepass(Request $req){
        $token = $req->input('token');
        $cp = $req->input('cp');
        $np = $req->input('np');

        if($token == null || empty($token) || empty($cp) || empty($np)){
            return response()->json([
                'isError' => true,
                'isLoggedIn' => true,
                'message' => 'None of the fields can be empty.'
             ]);
        }else if(strlen($np) < 6) {
            return response()->json([
                'isError' => true,
                'isLoggedIn' => true,
                'message' => 'Password length must be at least six characters.'
             ]);
        }else {
            $user= User::getUserByToken($token);
            if($user){
                if(Hash::check($cp, $user->password)){
                    $user->password = Hash::make($np);
                    if($user->save()){
                        return response()->json([
                            'isError' => false,
                            'isLoggedIn' => true,
                            'isChanged' => true,
                            'message' => 'Password Updated.'
                         ]);
                    }else {
                        return response()->json([
                            'isError' => true,
                            'isLoggedIn' => true,
                            'message' => 'Error occurred in updating the password. Please try again.'
                         ]);
                    }
                }else {
                    return response()->json([
                        'isError' => true,
                        'isLoggedIn' => true,
                        'message' => 'Invalid current password'
                     ]);
                }

            }else {
                return response()->json([
                    'isError' => true,
                    'isLoggedIn' => true,
                    'message' => 'No user Found'
                 ]);
            }
        }
    }


    public function getWishList(Request $req){
        $token = $req->input('token');

        if($token == null || empty($token)){
            return response()->json([
                'isError' => true,
                'isLoggedIn' => true,
                'message' => 'User must be provided.'
             ]);
        }else {
            $user= User::getUserByToken($token);
            if($user){
                $wl = WhishList::getWishListProducts($user->id);
                if($wl->count() > 0){
                    return response()->json([
                        'isError' => false,
                        'isLoggedIn' => true,
                        'isFound' => true,
                        'products' => $wl->get(),
                        'message' => 'loading...'
                     ]);
                }else {
                    return response()->json([
                        'isError' => false,
                        'isLoggedIn' => true,
                        'isFound' => false,
                        'message' => 'Your wishlist is empty.'
                     ]);
                }
            }else {
                return response()->json([
                    'isError' => true,
                    'isLoggedIn' => true,
                    'message' => 'Invalid User.'
                 ]);
            }
        }
    }


    public function addToWishList(Request $req){
        $token = $req->input('token');
        $product_id = $req->input('pid');

        if($token == null || empty($token) || $product_id == null || empty($product_id) || !is_numeric($product_id)){
            return response()->json([
                'isError' => true,
                'isLoggedIn' => true,
                'message' => 'User must be provided.'
             ]);
        }else {
            $user= User::getUserByToken($token);
            if($user){
                $wlCheck = WhishList::where(['product_id' => $product_id,'user_id' => $user->id]);

                if($wlCheck->count() > 0){
                    if($wlCheck->first()->delete()){
                        $wl = WhishList::getWishListProducts($user->id)->get();
                        return response()->json([
                            'isError' => false,
                            'isLoggedIn' => true,
                            'isDeleted' => true,
                            'products' => $wl,
                            'message' => 'The product is removed from your wishlist.'
                         ]);
                    }else {
                        return response()->json([
                            'isError' => true,
                            'isLoggedIn' => true,
                            'isDeleted' => false,
                            'message' => 'Error occurred in removing the product from your wishlist. Please try again.'
                         ]);
                    }

                }else {
                $wl = new WhishList();
                $wl->user_id = $user->id;
                $wl->product_id = $product_id;
                if($wl->save()){
                    $wl = WhishList::getWishListProducts($user->id)->get();
                    return response()->json([

                        'isError' => false,
                        'isLoggedIn' => true,
                        'isAdded' => true,
                        'products' => $wl,
                        'message' => 'Product Added to your wish list.'
                     ]);
                }else {
                    return response()->json([
                        'isError' => false,
                        'isLoggedIn' => true,
                        'isAdded' => false,
                        'message' => 'Error occurred in Adding the product to wishlist.'
                     ]);
                }
            }
            }else {
                return response()->json([
                    'isError' => true,
                    'isLoggedIn' => true,
                    'message' => 'Invalid User.'
                 ]);
            }
        }
    }

    public function returnCart(Request $req){

        $cart = $req->input('ct');
        $token = $req->input('token');
        $name = $req->input('name');
        $address = $req->input('address');
        $company = $req->input('company');
        $town = $req->input('town');
        $postalcode = $req->input('postalcode');
        $email = $req->input('email');
        $phone = $req->input('phone');

        if(empty($cart) || empty($token) || empty($name) || empty($address) || empty($company) || empty($town) || empty($postalcode) ||
        empty($email) || empty($phone)){
            return response()->json([
                'isError' => true,
                'isCartSaved' => false,
                'message' => 'none of the fields can be empty'
            ]);
        }else {
            $user= User::getUserByToken($token);
            if($user){
                $cart = base64_decode($cart);
                $cart = json_decode($cart);
                $ck = new CK();
                $ck->firstname = $name;
                $ck->address = $address;
                $ck->email = $email;
                $ck->phonenumber = $phone;
                $ck->town = $town;
                $ck->postalcode = $postalcode;
                $ck->user_id = $user->id;
                $ck->products_quantity = 0;
                $ck->total_price = 0;
                $ck->session_id = "Processed from mobile app";

                if($ck->save()){
                    $quantity = 0;
                    $tprice = 0;
                    $isDone = false;
                    foreach($cart as $c){
                        $p = Product::where(['product_id' => $c->product_id]);
                        if($p->count() > 0){
                            $p = $p->first();
                            $quantity = $quantity + $c->quantity_ordered;
                            $tprice = $tprice + $p->product_price * $c->quantity_ordered;
                            $or = new Order();
                            $or->checkout_id = $ck->id;
                           // $or->user_id = $user->id;
                            $or->product_id = $p->product_id;
                            $or->product_price = $p->product_price;
                            $or->quantity_ordered = $c->quantity_ordered;
                            $or->total_ordered_product_price = $p->product_price * $c->quantity_ordered;

                            if($or->save()){
                                $isDone = true;
                                continue;
                            }else {
                                $ck->delete();
                                $isDone = false;
                                return response()->json([
                                    'isError' => true,
                        'isCartSaved' => false,
                                    'message' => 'Error occurred in processing the checkout. Please try again.'
                                ]);
                            }
                        }else {
                            return response()->json([
                                'isError' => true,
                        'isCartSaved' => false,
                                'message' => 'Invalid product(s) supplied.'
                            ]);
                        }
                    }

                    if($isDone){
                        $ck->products_quantity = $quantity;
                        $ck->total_price = $tprice;
                        if($ck->save()){
                            return response()->json([
                                'isError' => false,
                                'isCartSaved' => true,
                                'ck' => $ck,
                                'message' => 'Proceed to payment.'
                            ]);
                        }else {
                            return response()->json([
                                'isError' => true,
                                'isCartSaved' => false,
                                'message' => 'Not done.'
                            ]);
                        }
                    }
                }else {
                    return response()->json([
                        'isError' => true,
                        'isCartSaved' => false,
                        'message' => 'Error occurred in processing the checkout. Please try again.'
                    ]);
                }

            }else {
                return response()->json([
                    'isError' => true,
                    'isCartSaved' => false,
                    'message' => 'Please login to checkout.'
                ]);
            }
        }



        return response()->json([
            'ids' => $ids
        ]);
        //var_dump($ids);
        exit();
        $products = Product::whereIn('product_id',$cart);
        if($products->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'proudcts' => $products->get(),
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No products found'
             ]);
        }
    }


    public function addToWishListProductsTab(Request $req){
        $token = $req->input('token');
        $product_id = $req->input('pid');

        if($token == null || empty($token) || $product_id == null || empty($product_id) || !is_numeric($product_id)){
            return response()->json([
                'isError' => true,
                'isLoggedIn' => true,
                'message' => 'User must be provided.'
             ]);
        }else {
            $user= User::getUserByToken($token);
            if($user){
                $wlCheck = WhishList::where(['product_id' => $product_id,'user_id' => $user->id]);

                if($wlCheck->count() > 0){
                    if($wlCheck->first()->delete()){
                        $products = Product::getLoggedInUserProducts($user->id)->get();
                        return response()->json([
                            'isError' => false,
                            'isLoggedIn' => true,
                            'isDeleted' => true,
                            'products' => $products,
                            'message' => 'The product is removed from your wishlist.'
                         ]);
                    }else {
                        return response()->json([
                            'isError' => true,
                            'isLoggedIn' => true,
                            'isDeleted' => false,
                            'message' => 'Error occurred in removing the product from your wishlist. Please try again.'
                         ]);
                    }

                }else {
                $wl = new WhishList();
                $wl->user_id = $user->id;
                $wl->product_id = $product_id;
                if($wl->save()){
                    $products = Product::getLoggedInUserProducts($user->id)->get();

                    return response()->json([

                        'isError' => false,
                        'isLoggedIn' => true,
                        'isAdded' => true,
                        'products' => $products,
                        'message' => 'Product Added to your wish list.'
                     ]);
                }else {
                    return response()->json([
                        'isError' => false,
                        'isLoggedIn' => true,
                        'isAdded' => false,
                        'message' => 'Error occurred in Adding the product to wishlist.'
                     ]);
                }
            }
            }else {
                return response()->json([
                    'isError' => true,
                    'isLoggedIn' => true,
                    'message' => 'Invalid User.'
                 ]);
            }
        }
    }



    public function register(Request $req){
        $name = $req->input('name');
        $email = $req->input('email');
        $password = $req->input('password');
        $confirmpassword = $req->input('cpass');
        if(empty($password) || empty($email) || empty($confirmpassword) || empty($name)){
            return response()->json([
                'isError' => true,
                'isRegistered' => false,
                'message' => 'None of the field can be empty.'
             ]);
        }elseif ($password !== $confirmpassword) {
            return response()->json([
                'isError' => true,
                'isRegistered' => false,
                'message' => 'Passwords mismatched.'
             ]);
        }
        else {
            $checkEmail = User::where(['email' => $email])->count();

        if($checkEmail > 0){
            return response()->json([
                'isError' => true,
                'isRegistered' => false,
                'message' => 'Email is already taken. Please use another one.'
             ]);
            }else {


                 $user = new User();
                 $user->name = $name;
                 $user->email = $email;
                 $user->role_name = "common";
                 $user->role = 0;
                 $user->password = Hash::make($password);

                 $token = Hash::make(time().$user->id.$user->name.time()*4);
                 $user->token = $token;
                 if($user->save()){
                     return response()->json([
                         'isError' => false,
                         'isRegistered' => true,
                         'user' => $user,
                         'message' => 'Registeration was successfull.'
                      ]);



        }else {
            return response()->json([
                'isError' => true,
                'isRegistered' => false,
                'message' => 'Error occurred during registeration. Please try again.'
             ]);
        }
    }
    }

}

public function getUserUnPaidCheckouts(Request $req){
    $token = $req->input('token');
    $user= User::getUserByToken($token);
    if($user){
        $ck = CK::getUnPaidCheckoutsForAPIs($user->id);
        if($ck->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'cks' => $ck->get(),
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No Unpaid checkout found'
             ]);
        }
    }else {
        return response()->json([
            'isError' => true,
            'isFound' => false,
            'message' => 'You are not logged in to perform this action.'
         ]);
    }

}

public function getUnPaidCheckoutProducts(Request $req){
    $token = $req->input('token');
    $ckid = $req->input('ckid');
    if(empty($token) || $token == null || empty($ckid) || $ckid == null || !is_numeric($ckid)){
        return response()->json([
            'isError' => true,
            'isFound' => false,
            'message' => 'Arguments must be provided.'
         ]);
    }else {
    $user= User::getUserByToken($token);
    if($user){
        $ck = CK::getSavedCheckout($ckid,$user->id);
        if($ck->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'products' => $ck->get(),
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No Products found in checkout'
             ]);
        }
    }else {
        return response()->json([
            'isError' => true,
            'isFound' => false,
            'message' => 'You are not logged in to perform this action.'
         ]);
    }

}
}


public function getUserPaidCheckouts(Request $req){
    $token = $req->input('token');
    $user= User::getUserByToken($token);
    if($user){
        $ck = CK::getPaidCheckoutsForAPIs($user->id);
        if($ck->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'cks' => $ck->get(),
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No paid checkouts found'
             ]);
        }
    }else {
        return response()->json([
            'isError' => true,
            'isFound' => false,
            'message' => 'You are not logged in to perform this action.'
         ]);
    }

}


public function getPaidCheckoutProducts(Request $req){
    $token = $req->input('token');
    $ckid = $req->input('ckid');
    if(empty($token) || $token == null || empty($ckid) || $ckid == null || !is_numeric($ckid)){
        return response()->json([
            'isError' => true,
            'isFound' => false,
            'message' => 'Arguments must be provided.'
         ]);
    }else {
    $user= User::getUserByToken($token);
    if($user){
        $ck = CK::getSavedCheckout($ckid,$user->id);
        if($ck->count() > 0){
            return response()->json([
               'isError' => false,
               'isFound' => true,
               'products' => $ck->get(),
               'message' => 'Loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isFound' => false,
                'message' => 'No Products found in checkout'
             ]);
        }
    }else {
        return response()->json([
            'isError' => true,
            'isFound' => false,
            'message' => 'You are not logged in to perform this action.'
         ]);
    }

}
}


}
