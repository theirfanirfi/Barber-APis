 @if(!empty($rvs))
 @if($rvs->count() > 0)
 <!-- ========================================= RECENTLY VIEWED ========================================= -->
            <section id="recently-reviewd" class="wow fadeInUp">
                <div class="container">
                    <div class="carousel-holder hover">

                        <div class="title-nav">
                            <h2 class="h1">Recently Viewed</h2>
                            <div class="nav-holder">
                                <a href="#prev" data-target="#owl-recently-viewed" class="slider-prev btn-prev fa fa-angle-left"></a>
                                <a href="#next" data-target="#owl-recently-viewed" class="slider-next btn-next fa fa-angle-right"></a>
                            </div>
                        </div><!-- /.title-nav -->

                        <div id="owl-recently-viewed" class="owl-carousel product-grid-holder">

                            @foreach($rvs->get() as $r)
                            <div class="no-margin carousel-item product-item-holder size-small hover">
                                <div class="product-item">
                                    <div class="ribbon red"><span>sale</span></div>
                                    <div class="image">
                                        <img alt="" src="{{ $r->product_image }}" data-echo="{{ $r->product_image }}" />
                                    </div>
                                    <div class="body">
                                        <div class="title">
                                            <a href="single-product.html">{{ $r->product_name }}</a>
                                        </div>
                                        <div class="brand">{{ $r->cat_title }}</div>
                                    </div>
                                    <div class="prices">
                                        <div class="price-current text-right">{{ $r->product_price }}</div>
                                    </div>
                                    <div class="hover-area">
                                        <div class="add-cart-button">
                                            <a href="{{ route('addtocart',['id' => $r->product_id]) }}" class="le-button">Add to Cart</a>
                                        </div>
                                        <div class="wish-compare">
                                            <a class="btn-add-to-wishlist" href="{{ route('addtowhishlist',['id' => $r->product_id]) }}">Add to Wishlist</a>
                                        </div>
                                    </div>
                                </div><!-- /.product-item -->
                            </div><!-- /.product-item-holder -->
@endforeach

                        </div><!-- /#recently-carousel -->

                    </div><!-- /.carousel-holder -->
                </div><!-- /.container -->
            </section><!-- /#recently-reviewd -->
            <!-- ========================================= RECENTLY VIEWED : END ========================================= -->
@endif
@endif
