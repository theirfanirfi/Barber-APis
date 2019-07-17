@extends('Frontend.MasterLayout')
@section('content')
<div id="single-product">
        <div class="container">

            <div class="no-margin col-xs-12 col-sm-6 col-md-5 gallery-holder">
                <div class="product-item-holder size-big single-product-gallery small-gallery">

                    <div id="owl-single-product" class="owl-carousel">
                        <div class="single-product-gallery-item" id="slide1">
                            <a data-rel="prettyphoto" href="{{ $product->product_image }}">
                                <img class="img-responsive" alt="" src="{{ $product->product_image }}" data-echo="{{ $product->product_image }}" />
                            </a>
                        </div><!-- /.single-product-gallery-item -->

                    </div><!-- /.single-product-slider -->


                </div><!-- /.single-product-gallery -->
            </div><!-- /.gallery-holder -->


            <div class="no-margin col-xs-12 col-sm-7 body-holder">
                <div class="body">
                    <div class="availability"><label>Availability:</label><span class="available">  in stock</span></div>

                    <div class="title"><a href="#">{{ $product->product_name }}</a></div>
                    <div class="brand">{{$product->getCat()}}</div>


                    <div class="buttons-holder">
                        <a class="btn-add-to-wishlist" href="{{ route('addtowishlist',['id' => $product->product_id]) }}">add to wishlist</a>
                    </div>

                    <div class="excerpt">
                    {{$product->product_desc}}
                    </div>

                    <div class="prices">
                        <div class="price-current">${{ $product->product_price }}</div>
                     <!--   <div class="price-prev">Stock: {{ $product->available }}</div> -->
                    </div>

                    <div class="qnt-holder">
                        <div class="le-quantity">
                            <form action="{{ route('addtocartproduct') }}" method="post">

                                <a class="minus" href="#reduce"></a>
                                <input name="quantity" readonly="readonly" type="text" value="1" />
                                <a class="plus" href="#add"></a>
                        </div>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}" />
                        <button type="submit" id="addto-cart" href="{{ route('addtocart',['id' => $product->product_id]) }}" class="le-button huge">add to cart</button>
                    </form>

                    </div><!-- /.qnt-holder -->
                </div><!-- /.body -->

            </div><!-- /.body-holder -->
        </div><!-- /.container -->
    </div><!-- /.single-product -->


@endsection
