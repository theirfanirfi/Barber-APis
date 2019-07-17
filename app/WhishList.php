<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class WhishList extends Model
{
    //
    protected $table = "wishlist";

    public static function getWishListProducts($user_id){
        return DB::table('wishlist')->where(['user_id' => $user_id])
        ->leftjoin('products',['products.product_id'=> 'wishlist.product_id'])
        ->select('products.product_id','product_desc','product_name','product_price','cat_id','quantity','sold','available','product_image','wishlist.id');
    }
}
