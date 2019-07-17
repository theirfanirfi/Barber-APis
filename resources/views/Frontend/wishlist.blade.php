@extends('Frontend.MasterLayout')
@section('content')

<div class="main-content" id="main-content">
        <div class="row">
            <div class="col-lg-10 center-block page-wishlist style-cart-page inner-bottom-xs">

                <div class="inner-xs">
                    <div class="page-header">
                        <h2 class="page-title">My Wishlist</h2>
                    </div>
                </div><!-- /.section-page-title -->
                @if($wl->count() > 0)
                <div class="items-holder">
                    <div class="container-fluid wishlist_table">

                        @foreach($wl->get() as $w)
                        <div class="row cart-item cart_item" id="yith-wcwl-row-1">

                            <div class="col-xs-12 col-sm-1 no-margin">
                                <a title="Remove this product" class="remove_from_wishlist remove-item" href="{{ route('removefromwishlist',['id' => $w->id]) }}">×</a>
                            </div>

                            <div class="col-xs-12 col-sm-1 no-margin">
                                <a href="single-product.html">
                                    <img width="73" height="73" alt="Canon PowerShot Elph 115 IS" class="attachment-shop_thumbnail wp-post-image" src="{{ $w->product_image }}">
                                </a>
                            </div>
                            <div class="col-xs-12 col-sm-4 no-margin">
                                <div class="title">
                                    <a href="{{ route('product',['id' => $w->product_id]) }}">{{ $w->product_name }}</a>
                                </div><!-- /.title -->
                                <div>
                                    @if($w->quantity == $w->sold)
                                    <span class="label label-danger wishlist-out-of-stock">Out of Stock</span>
                                    @else
                                    <span class="label label-success wishlist-in-stock">In Stock</span>
                                    @endif

                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-3 no-margin">
                                <div class="price">
                                    <span class="amount">${{ $w->product_price }}</span>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-3 no-margin">
                                <div class="text-right">
                                    <div class="add-cart-button">
                                            @if($w->quantity > $w->sold)
                                    <a class="le-button add_to_cart_button product_type_simple" href="{{ route('addtocart',['id' => $w->product_id]) }}">Add to cart</a>
                                            @else
                                            <a class="le-button disabled product_type_simple" href="">Add to cart</a>

                                            @endif

                                    </div>
                                </div>
                            </div>

                        </div><!-- /.cart-item -->
@endforeach


                    </div><!-- /.wishlist-table -->
                </div><!-- /.items-holder -->

                @else
                <h1>Your WishList is empty.</h1>
                @endif
            </div><!-- .large-->
        </div><!-- .row-->
    </div>

@endsection
