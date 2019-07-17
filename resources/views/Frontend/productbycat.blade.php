@extends('Frontend.MasterLayout')
@section('content')
<div id="products-tab" class="wow fadeInUp">
        <div class="container">

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="featured">
                        <div class="product-grid-holder">

                            @foreach ($products as $p)
                            <div class="col-sm-4 col-md-3  no-margin product-item-holder hover">
                                <div class="product-item">
                                    <div class="ribbon red"><span>sale</span></div>
                                    <div class="image">
                                        <img alt="" src="{{ $p->product_image }}" data-echo="{{ $p->product_image }}" style="width:100%;height:200px;" />
                                    </div>
                                    <div class="body">
                                      <!--  <div class="label-discount green">-50% sale</div> -->
                                        <div class="title">
                                            <a href="single-product.html">{{ $p->product_name }}</a>
                                        </div>
                                        <div class="brand">sony</div>
                                    </div>
                                    <div class="prices">
                                            <div class="price-current pull-left">Available: {{ $p->available }}</div>
                                        <div class="price-current pull-right">${{ $p->product_price }}</div>
                                    </div>

                                    <div class="hover-area">
                                        <div class="add-cart-button">
                                                <a href="{{ route('addtocart',['id' => $p->product_id]) }}" class="le-button">add to cart</a>
                                        </div>
                                        <div class="wish-compare">
                                            <a class="btn-add-to-wishlist" href="{{ route('addtowishlist',['id' => $p->product_id]) }}">add to wishlist</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach



                        </div>


                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
