<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Product as Pd;
use App\Category as Cat;
use App\RecentlyViewed as RV;
use App\WhishList as WL;
use App\Order;
use App\Checkout as CK;
use Auth;
use Illuminate\Support\Facades\Hash;
class FrontendController extends Controller
{
    //

    public function index(){
        //Session()->forget(['cart','total_cart_cost']);
        $rv = "";
        if(Auth::check()){
            $rv = new RV();
            $user = Auth::user();
            $rv = $rv->getRV($user->id);
            // if($rv->count() > 0){
            //     $rv = $rv->get();
            // }
        }

        $cats = Cat::all();
        $products = Pd::getProducts()->inRandomOrder()->limit(8)->get();
        $rpro = Pd::getProducts()->latest()->orderBy('product_id','DESC')->limit(8)->get();
        return view('Frontend.home',['cats' => $cats, 'products' => $products, 'recentproducts' => $rpro,'rvs' => $rv]);
    }

    public function addtocart($id){
        $product = Pd::where(['product_id' => $id]);
        if($product->count() > 0){
            if(Session()->has('cart')){
                $product = $product->first();
                $arr = Session()->get('cart');
                $price = Session()->get('total_cart_cost');
                 $pro = $arr['pro'];
                $num = $arr['num'];


                if(!array_key_exists($id, $pro)){
                    $pro[$product->product_id] = array('product_id' => $product->product_id,'product_name' => $product->product_name, 'quantity' => 1);
                    $arr['pro'] = $pro;
                    $num = $num + 1;
                    $arr['num'] = $num;
                    $price = $price + ($product->product_price * 1);
                    $arr['pro'] = $pro;
                    Session()->put('cart',$arr);
                    Session()->put('total_cart_cost',$price);
                    return redirect()->back()->with('success','Product Added to cart.');
                }else {
                    return redirect('/product/'.$id)->with('info','Product is already in the cart.');
                }

            }else {
                $product = $product->first();
                $arr = array();
                $pro = array();
                $pro[$product->product_id] = array('product_id' => $product->product_id,'product_name' => $product->product_name, 'quantity' => 1);
               $num = 1;
               $arr['num'] = $num;
               $arr['pro'] = $pro;

                Session()->put('cart',$arr);
                Session()->put('total_cart_cost',$product->product_price);
                return redirect()->back()->with('success','Product Added to cart.');
            }
        }else {
            return redirect('/')->with('error','No such product exists in the store.');
        }
    }

    public function productsByCat($id){
        $cat = Cat::where(['cat_id' => $id]);
        if($cat->count() > 0){
            $products = Pd::where(['cat_id' => $id])->get();
            $cats = Cat::all();
            return view('Frontend.productbycat',['products' => $products,'cats' => $cats]);
        }else {
            return redirect()->back()->with('error','No such category exists in the system.');
        }
    }

    public function singleproduct($id){
        //$product = Pd::where(['product_id' => $id]);
        $product = Pd::where(['product_id' => $id]);
        if($product->count() > 0){
            $product = $product->first();
            $cats = Cat::all();
            return view('Frontend.singleproduct',['product' => $product,'cats' => $cats]);
        }else {
            return redirect()->back()->with('error','No such Product exists in the system.');
        }
    }

    public function addtocartproduct(Request $req){
        $id = $req->input('product_id');
        $quantity = $req->input('quantity');
        $product = Pd::where(['product_id' => $id]);
        if($quantity < 0 || !is_numeric($quantity)){
            return redirect()->back()->with('error','Invalid number of product inputs.');
        }else if($product->count() > 0){
            if(Session()->has('cart')){
                $product = $product->first();
                $arr = Session()->get('cart');
               $pro = $arr['pro'];
               $num = $arr['num'];
               $price = Session()->get('total_cart_cost');
               if(!array_key_exists($id, $pro)){
               $pro[$product->product_id] = array('product_id' => $product->product_id,'product_name' => $product->product_name, 'quantity' => $quantity);
               $arr['pro'] = $pro;
               $num = $num + $quantity;
               $arr['num'] = $num;
               $price = $price + ($product->product_price * $quantity);
               }else {
                   $modifyProductInTheArray = $pro[$id];
                   $num = $arr['num'];
                   $num = $num - $modifyProductInTheArray['quantity'];
                   $price = $price - $product->product_price * $modifyProductInTheArray['quantity'];
                   $modifyProductInTheArray['quantity'] = $quantity;
                   unset($pro[$id]);
                   $pro[$id] = $modifyProductInTheArray;
                   $price = $price + ($product->product_price * $quantity);
                   $arr['pro'] = $pro;
                   $num = $num + $quantity;
                   $arr['num'] = $num;


               }


                Session()->put('cart',$arr);
                Session()->put('total_cart_cost',$price);
                return redirect()->back()->with('success','Product Added to cart.');
            }else {
                $product = $product->first();
                $arr = array();
                $pro = array();
                $arr['num'] = $quantity;
              $pro[$product->product_id] = array('product_id' => $product->product_id,'product_name' => $product->product_name, 'quantity' => $quantity);
                $arr['pro'] = $pro;
                Session()->put('cart',$arr);
                Session()->put('total_cart_cost',$product->product_price * $quantity);
                return redirect()->back()->with('success','Product Added to cart.');
            }
        }else {
            return redirect('/')->with('error','No such product exists in the store.');
        }
    }

    public function addtowishlist($id){
        if($id < 0 || !is_numeric($id)){
            return redirect()->back()->with('error','Invalid product inputs.');
        }else {
            $product = Pd::where(['product_id' => $id]);
            if($product->count() > 0){
                $user = Auth::user();
                $wl = new WL();
                $wl->product_id = $id;
                $wl->user_id = $user->id;
                if($wl->save()){
                    $wls = WL::where(['user_id' => $user->id])->count();
                    Session()->put('wl',$wls);
                    return redirect()->back()->with('success','Product Added to your WishList.');
                }else {
            return redirect()->back()->with('error','Error occurred in adding the product to your WishList. Try Again.');
                }
            }else {
                return redirect()->back()->with('error','No such product exists in the store.');
            }
        }
    }

    public function wishlist(){
        $user = Auth::user();
        $wls = WL::getWishListProducts($user->id);
        $cats = Cat::all();
        return view('Frontend.wishlist',['user' => $user,'wl' => $wls,'cats' => $cats]);
    }

    public function removefromwishlist($id){
        $user = Auth::user();
        $wl = WL::where(['user_id' => $user->id,'id' => $id]);
        if($wl->count() > 0){
            if($wl->first()->delete()){
                $wls = WL::where(['user_id' => $user->id])->count();
                Session()->forget('wl');
                Session()->put('wl',$wls);
                return redirect()->back()->with('success','Item is removed from your wish list.');

            }else {
                return redirect()->back()->with('error','Error occurred in removing the item. Please try again.');
            }
        }else {
            return redirect()->back()->with('error','No such item exists in the your wish list to remove.');
        }
    }

    public function cart(){
        $cats = Cat::all();
        $products = "";
        $quantities = array();
       if(Session()->has('cart')){
        $cart = Session()->get('cart');
        $pro = $cart['pro'];
       // dd(array_keys($pro));
       $keys = array_keys($pro);
        $ids = array();
        $i = 0;
        while($i < count($keys)){
            $opro = $pro[$keys[$i]];
            $oproid = $opro['product_id'];
             $ids[] = $oproid;
             $quantities[$oproid] = $opro['quantity'];
            $i++;
       }

       Session()->put('cart_quantities',$quantities);
       $products = Pd::whereIn('product_id',$ids)->get();

        return view('Frontend.cart',['quantities' => $quantities,'cats' => $cats, 'products' => $products]);
       }else {
        return view('Frontend.cart',['quantities' => $quantities,'cats' => $cats, 'products' => $products]);
       }

    }

    public function removeProductFromCart($id){
        if(Session()->has('cart')){
        $cart = Session()->get('cart');
        $pro = $cart['pro'];
        $product = Pd::where(['product_id' => $id]);
        if($product->count() > 0){
            $product = $product->first();
        if(array_key_exists($id,$pro)){
            $rpro = $pro[$id];
            $num = $cart['num'];
            $num = $num - $rpro['quantity'];
            $price = Session()->get('total_cart_cost');

            $price = $price - $product->product_price * $rpro['quantity'];
            unset($pro[$id]);
            $cart['pro'] = $pro;
            $cart['num'] = $num;


            Session()->put('cart',$cart);
            Session()->put('total_cart_cost',$price);
            return redirect()->back()->with('success','Product removed from cart.');
        }
        }else {
            return redirect()->back()->with('error','You have not added products to the cart yet.');
        }
    }else {
        //no such product
        return redirect()->back()->with('error','No such product exists in the system.');

    }
    }

    public function searchProducts(Request $req){
        $name= $req->input('product_name');
        $cat_id =  $req->input('cat_id');
        $cats = Cat::all();
        if(empty($name) || empty($cat_id)){
        return redirect()->back()->with('error','Product title and category must be provide to search.');
        }else if(is_numeric($cat_id) && $cat_id != "all"){
            $scat = Cat::where(['cat_id' => $cat_id]);
            if($scat->count() > 0){
                $products = Pd::where(['cat_id' => $cat_id])->where('product_name', 'like', '%'.$name.'%');
                return view('Frontend.searchproduct',['products' => $products, 'cats' => $cats]);
            }else {
            return redirect()->back()->with('error','No such products category exists.');
            }
        }else if(!is_numeric($cat_id) && $cat_id == "all"){
            $products = Pd::where('product_name', 'like', '%'.$name.'%');
            return view('Frontend.searchproduct',['products' => $products, 'cats' => $cats]);
        }else {
            return redirect()->back()->with('error','Error.');
        }
    }

    public function checkout(){
        $cats = Cat::all();
        $user = Auth::user();

        $products = "";
        $quantities = array();
       if(Session()->has('cart')){
        $cart = Session()->get('cart');
        $pro = $cart['pro'];
       // dd(array_keys($pro));
       $keys = array_keys($pro);
        $ids = array();
        $i = 0;
        while($i < count($keys)){
            $opro = $pro[$keys[$i]];
            $oproid = $opro['product_id'];
             $ids[] = $oproid;
             $quantities[$oproid] = $opro['quantity'];
            $i++;
       }

       Session()->put('cart_quantities',$quantities);
       $products = Pd::whereIn('product_id',$ids)->get();

        return view('Frontend.checkout',['quantities' => $quantities,'cats' => $cats, 'products' => $products,'user' => $user]);
       }else {
        return view('Frontend.checkout',['quantities' => $quantities,'cats' => $cats, 'products' => $products,'user' => $user]);
       }

        //return view('Frontend.checkout',['cats' => $cats,'user' => $user]);
    }

    public function placeorder(Request $req){


        $fullname = $req->input('name');
        $company = $req->input('company');
        $address = $req->input('address');
        $town = $req->input('citytown');
        $postalcode = $req->input('postcode');
        $email = $req->input('email');
        $phone = $req->input('phone');
        $isCartSaved = true;

        if(empty($fullname) || empty($address) || empty($town) || empty($postalcode) || empty($email) || empty($phone)){
            return redirect()->back()->with('error','None of the fields can be empty.');
        }else {

        $user = Auth::user();
        $products = "";
        $quantities = array();
       if(Session()->has('cart')){
        $cart = Session()->get('cart');
        $pro = $cart['pro'];
       // dd(array_keys($pro));
       $keys = array_keys($pro);
        $ids = array();
        $i = 0;

        while($i < count($keys)){
            $opro = $pro[$keys[$i]];
            $oproid = $opro['product_id'];
             $ids[] = $oproid;
             $quantities[$oproid] = $opro['quantity'];
            $i++;
       }

       $num = $cart['num'];
       $products = Pd::whereIn('product_id',$ids)->get();
       $session_id = session()->getId();
       $num = $cart['num'];
       $price = Session()->get('total_cart_cost');

       $checkWhetherCheckoutExistsOrNot = CK::Where(['session_id' => $session_id,'user_id' => $user->id])->count();

       if($checkWhetherCheckoutExistsOrNot > 0){
           //do nothing
           $dck = CK::Where(['session_id' => $session_id,'user_id' => $user->id,'is_paid' => 0]);
           if($dck->count() > 0){
               $dck = $dck->first();
               if($dck->delete()){
                   return redirect('/user/checkout')->with('error','Please try again.');
               }else {
                return redirect('/user/checkout')->with('error','Please try again.');

               }
           }

       }else {

            // foreach($products as $p){
            //     echo $p->product_id. " : name : ".$p->product_name. " : quantity: ".$quantities[$p->product_id].
            //     ": price: ".$p->product_price." : total cost : ".$p->product_price*$quantities[$p->product_id]."</br/>";
            // }

           $ck = new CK();
           $ck->firstname = $fullname;
           $ck->company = $company;
           $ck->address = $address;
           $ck->town = $town;
           $ck->postalcode = $postalcode;
           $ck->email = $email;
           $ck->postalcode = $postalcode;
           $ck->phonenumber = $phone;
           $ck->total_price = $price;
           $ck->products_quantity = $num;
           $ck->user_id = $user->id;
           $ck->session_id = $session_id;
           $ck->is_checkout = 1;
           if($ck->save()){
               foreach($products as $p){
                 $o = new Order();
                 $o->checkout_id = $ck->id;
                 $o->product_id = $p->product_id;
                 $o->product_price = $p->product_price;
                 $o->quantity_ordered = $quantities[$p->product_id];
                 $o->total_ordered_product_price = $p->product_price*$quantities[$p->product_id];
                 if(!$o->save()){
                      $isCartSaved = false;
                     $ck->delete();
                     return redirect()->back()->with('error','Error occurred in saving the cart. Please try again.');
                     break;
                     exit();
                 }

               }

               if(!$isCartSaved){
                return redirect()->back()->with('error','Error occurred in saving the cart. Please try again.');
               }else {
                   return redirect('/user/pay');
               }
           }else {
            return redirect()->back()->with('error','Error occurred in saving the cart. Please try again.');
           }


       }


    }else {
        return redirect()->back()->with('error','Your Cart is empty.');
    }
}
}


public function myaccount(){
    $user = Auth::user();
    $cats = Cat::all();
    $paidCheckOuts = CK::where(['user_id' => $user->id,'is_paid' => 1]);
    $unPaidCheckOuts = CK::where(['user_id' => $user->id,'is_paid' => 0]);
    return view('Frontend.myaccount',['user' => $user, 'cats' => $cats, 'paid' => $paidCheckOuts, 'unpaid' => $unPaidCheckOuts]);
}

public function updateaccount(Request $req){
    $name = $req->input('name');
    $email = $req->input('email');

    if(empty($name) || empty($email)){
    }else{
        $user = Auth::user();

        if($email !== $user->email){
            $checkEmail = User::where(['email' => $email])->count();
            if($checkEmail > 0){
                return redirect()->back()->with('error','The email is already taken. Use another one.');
            }else {
                $user->email = $email;
            }
        }

        if($name !== $user->name){
            $user->name = $name;
        }




        if($user->save()){
            return redirect()->back()->with('success','Account updated.');
        }else{
            return redirect()->back()->with('error','Error occurred in updating the account. Please try again.');
        }
    }
}

    public function changepassword(Request $req){
        $cpass = $req->input('current_pass');
        $npass = $req->input('new_pass');
        $user = Auth::user();
            if(empty($cpass) || empty($npass)){
                return redirect()->back()->with('error','Both the password fields are required.');
            }else if(strlen($npass) < 6){
                return redirect()->back()->with('error','New password length must be at least six characters long.');
            }else {
                if(Hash::check($cpass, $user->password)){
                    $user->password = Hash::make($npass);
                    if($user->save()){
                        return redirect()->back()->with('success','Password successfully changed..');
                    }else {
            return redirect()->back()->with('error','Error occurred in updating the password. Please try again.');

                    }
                }else{
                    return redirect()->back()->with('error','Invalid current password');
                }
            }

    }

    public function savedcart($id){
       // $isPaid = false;

        if(!is_numeric($id) || $id == null || empty($id)){
            return redirect()->back()->with('error','Checkout Id must be provided.');
        }else {
            $user = Auth::user();
        $ck = CK::getSavedCheckout($id,$user->id);
        if($ck->count() > 0){
           // $isPaid = $ck->first()->is_paid == 1 ? true : false;
            $ck = $ck->get();
            $cats = Cat::all();
            $sck = $ck->first();
            return view('Frontend.dbsavedcart',['user' => $user,'products' => $ck,'cats' => $cats,'checkout' => $sck]);
        }else {
            return redirect()->back()->with('error','Either the checkout does not exist, or it does not belong to you');

        }
        }
    }

    public function deleteproductsavedincart($id){
        if(!is_numeric($id) || $id == null || empty($id)){
            return redirect()->back()->with('error','Id must be provided.');
        }else {
            $user = Auth::user();
            $order = Order::where(['id' => $id]);
            if($order->count() > 0){
                $order = $order->first();
                $ck = CK::where(['id' => $order->checkout_id,'user_id' => $user->id]);
                if($ck->count() > 0){
                    $price = $order->total_ordered_product_price;
                    $quantity = $order->quantity_ordered;
                    if($order->delete()){
                        $ck = $ck->first();
                        $or = Order::where(['checkout_id' => $ck->id]);

                        if($or->count() > 0){
                        if($ck->total_price > 0){
                        $ck->total_price = $ck->total_price - $price;
                        $ck->products_quantity = $ck->products_quantity - $quantity;
                        $ck->save();
                        }
                        }else {
                            if($ck->delete()){
                                return redirect('/user/myaccount')->with('success','Checkout deleted. Because it was the last product in the checkout.');
                            }else {
                            return redirect()->back()->with('info','Product deleted, but error occurred during deleting the checkout. Delete the checkout manually.');
                            }
                        }


                        return redirect()->back()->with('success','Product deleted from the cart.');

                    }else {
                        return redirect()->back()->with('error','Error occurred in deleting the product from cart. Try again.');
                    }
                }else {
                    return redirect()->back()->with('error',"The checkout's product does not belong to you.");
                }
            }else {
            return redirect()->back()->with('error','No such product exists in the cart.');

            }
        }
    }

    public function deletesavedcheckout($id){
        if(!is_numeric($id) || $id == null || empty($id)){
            return redirect()->back()->with('error','Id must be provided.');
        }else {
            $user = Auth::user();
            $ck = CK::where(['id' => $id,'user_id' => $user->id,'is_paid' => 0]);
            if($ck->count() > 0){
                $ck = $ck->first();
                if($ck->delete()){
                    return redirect()->back()->with('success','Checkout deleted.');

                }else {
                    return redirect()->back()->with('error','Error occurred in deleting the checkout. Try again.');

                }
            }else {
            return redirect()->back()->with('error','No such checkout exists, or it does not belong to you.');

            }
        }
    }


    public function scheckout($id){

        if(!is_numeric($id) || empty($id) || $id == null){
            return redirect()->back()->with('error','Checkout must be provided to place order');
        } else {
        $cats = Cat::all();
        $user = Auth::user();
        $ck = CK::getSavedCheckout($id,$user->id);
        if($ck->count() > 0){
            $checkout = $ck->first();
            $ck = $ck->get();
        return view('Frontend.checkoutsaved',['checkout' => $checkout,'cats' => $cats, 'products' => $ck,'user' => $user]);

        }else {
            return redirect()->back()->with('error','No such checkout exists.');

        }

    }
    }



    public function placecheckoutorders(Request $req){


        $fullname = $req->input('name');
        $company = $req->input('company');
        $address = $req->input('address');
        $town = $req->input('citytown');
        $postalcode = $req->input('postcode');
        $email = $req->input('email');
        $phone = $req->input('phone');
        $checkout_id = $req->input('checkout_id');
        if(empty($fullname) || empty($address) || empty($town) || empty($postalcode) || empty($email) || empty($phone) || !is_numeric($checkout_id) || empty($checkout_id)){
            return redirect()->back()->with('error','None of the fields can be empty.');
        }else {
            $user = Auth::user();
       $checkout = CK::Where(['id' => $checkout_id,'user_id' => $user->id]);

       if($checkout->count() > 0){

        $checkout = $checkout->first();
        $checkout->firstname = $fullname;
        $checkout->address = $address;
        $checkout->town = $town;
        $checkout->postalcode = $postalcode;
        $checkout->email = $email;
        $checkout->phonenumber = $phone;

           if($checkout->save()){
               return redirect('/user/payforcart/'.$checkout_id);
           }else {
            return redirect('/user/checkout')->with('error','Please try again.');

           }

    }else {
        return redirect()->back()->with('error','The checkout does not exist or it does not belong to you.');
    }
}
}



}

