<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Order extends Model
{
    //
    protected $table = "order";

    public static function getOrderProducts($checkout_id){
        return DB::table('order')->where(['checkout_id' => $checkout_id])
        ->leftjoin('products',['products.product_id' => 'order.product_id']);
    }

    public static function getCheckoutWithOrder($session_id,$user_id){
        return DB::table('checkout')->where(['session_id' => $session_id,'user_id' => $user_id,'is_paid' => 0])
        ->leftjoin('order',['order.checkout_id' => 'checkout.id'])
        ->leftjoin('products',['products.product_id' => 'order.product_id']);
    }

    public static function getSavedCheckoutWithOrder($id,$user_id){
        return DB::table('checkout')->where(['checkout.id' => $id,'user_id' => $user_id,'is_paid' => 0])
        ->leftjoin('order',['order.checkout_id' => 'checkout.id'])
        ->leftjoin('products',['products.product_id' => 'order.product_id']);
    }
}
