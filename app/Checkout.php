<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Checkout extends Model
{
    //
    protected $table = "checkout";

    public function getNewOrders(){
        return DB::table('checkout')->where(['is_processed' => 0])
        ->leftjoin('order',['order.checkout_id' => 'checkout.id'])->get();
    }

    public static function getCheckout($session_id,$user_id){
        return Checkout::where(['session_id' => $session_id,'user_id' => $user_id,'is_paid' => 0]);
    }

    public static function getSavedCheckout($id,$user_id){
        return DB::table('checkout')->where(['checkout.id' => $id,'user_id' => $user_id])
        ->leftjoin('order',['order.checkout_id' => 'checkout.id'])
        ->leftjoin('products',['products.product_id' => 'order.product_id'])
        ->select('order.id as order_id','checkout.id as chk_id','order.*','checkout.*','products.*');
    }

    public static function getUnPaidCheckoutsForAPIs($user_id){
        return Checkout::where(['is_paid' => 0,'user_id' => $user_id]);
    }


    public static function getPaidCheckoutsForAPIs($user_id){
        return Checkout::where(['is_paid' => 1,'user_id' => $user_id]);
    }
}
