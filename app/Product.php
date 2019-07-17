<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category as Cat;
use DB;
class Product extends Model
{
    //
    protected $primaryKey = "product_id";

    public static function getProducts(){
        return DB::table('products')->leftjoin('categories',['products.cat_id' => 'categories.cat_id'])
        ->select('product_id','product_name','product_desc','product_image','products.cat_id','products.created_at','quantity','sold','available','cat_title','cat_title','product_price');
    }

    public static function getProductsByCat($cat_id){
        return DB::table('products')->where(['products.cat_id' => $cat_id])->leftjoin('categories',['products.cat_id' => 'categories.cat_id'])
        ->select('product_id','product_name','product_desc','product_image','products.cat_id','products.created_at','quantity','sold','available','cat_title','cat_title','product_price');
    }

    public function getCat(){
        return Cat::find($this->cat_id)->cat_title;
    }

    public static function getLoggedInUserProducts($user_id){
        return DB::table('products')->leftjoin('categories',['products.cat_id' => 'categories.cat_id'])
        ->leftjoin('wishlist',['wishlist.product_id' => 'products.product_id'])
        ->select('wishlist.product_id','wishlist.user_id','products.product_id','product_name','product_image','products.cat_id','products.created_at','quantity','sold','available','cat_title','cat_title',
        'product_price','product_desc',DB::raw("IF (wishlist.user_id = '".$user_id."','true','false') as isFav"));
    }

    public static function getProduct($product_id){
        return DB::table('products')->where(['product_id' => $product_id])
        ->leftjoin('categories',['categories.cat_id' => 'products.cat_id']);
    }
}
