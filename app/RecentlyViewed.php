<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product as Pd;
use DB;
class RecentlyViewed extends Model
{
    //
    public function getRV($user_id){
        return DB::table('recentlyviewed')->where(['user_id' => $user_id])
        ->leftjoin('products',['products.product_id' => 'recentlyviewed.product_id'])
        ->leftjoin('categories',['categories.cat_id' => 'products.product_id'])
        ->select('products.product_id','categories.cat_name','categories.cat_id','product_name','product_price','product_image','available');
    }
}
