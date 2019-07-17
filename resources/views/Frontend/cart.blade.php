@extends('Frontend.MasterLayout')
@section('content')
<section id="cart-page">
        <div class="container">
            <!-- ========================================= CONTENT ========================================= -->
            <div class="col-xs-12 col-md-9 items-holder no-margin">
                @if(!empty($products))
                @foreach ($products as $p)

                <div class="row no-margin cart-item">
                    <div class="col-xs-12 col-sm-2 no-margin">
                        <a href="{{ route('product',['id' => $p->product_id]) }}" class="thumb-holder">
                            <img class="lazy" alt="" src="{{ $p->product_image }}" />
                        </a>
                    </div>

                    <div class="col-xs-12 col-sm-5 ">
                        <div class="title">
                            <a href="{{ route('product',['id' => $p->product_id]) }}">{{ $p->product_name }}</a>
                        </div>
                        <div class="brand">{{ $p->getCat() }}</div>
                    </div>

                    <div class="col-xs-12 col-sm-3 no-margin">
                        <div class="quantity">
                            <div class="le-quantity">
                                <form>
                                    <a class="" href="#reduce"></a>
                                    <input name="quantity" readonly="readonly" type="text" value="{{  $quantities[$p->product_id] }}*${{ $p->product_price }}" />
                                    <a class="" href="#add"></a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2 no-margin">
                        <div class="price">
                            ${{ $p->product_price * $quantities[$p->product_id] }}
                        </div>
                        <a class="close-btn" href="{{ route('removeproductcart',['id' => $p->product_id]) }}"></a>
                    </div>
                </div><!-- /.cart-item -->
                @endforeach


            </div>
            <!-- ========================================= CONTENT : END ========================================= -->

            <!-- ========================================= SIDEBAR ========================================= -->

            <div class="col-xs-12 col-md-3 no-margin sidebar ">
                <div class="widget cart-summary">
                    <h1 class="border">shopping cart</h1>
                    <div class="body">
                        <ul class="tabled-data no-border inverse-bold">
                            <li>
                                <label>cart subtotal</label>
                                <div class="value pull-right">$@if(Session()->has('total_cart_cost')){{ Session()->get('total_cart_cost') }} @else 0 @endif</div>
                            </li>
                       <!--     <li>
                                <label>shipping</label>
                                <div class="value pull-right">free shipping</div>
                            </li> -->
                        </ul>
                        <ul id="total-price" class="tabled-data inverse-bold no-border">
                            <li>
                                <label>order total</label>
                                <div class="value pull-right">$@if(Session()->has('total_cart_cost')){{ Session()->get('total_cart_cost') }} @else 0 @endif</div>
                            </li>
                        </ul>
                        <div class="buttons-holder">
                            <a class="le-button big" href="{{ route('checkout') }}" >checkout</a>
                            <a class="simple-link block" href="{{ route('home') }}" >continue shopping</a>
                        </div>
                    </div>
                </div><!-- /.widget -->

            </div><!-- /.sidebar -->
            @else
            <h1>You have not added any product to the cart</h1>
            @endif

            <!-- ========================================= SIDEBAR : END ========================================= -->
        </div>
    </section>
@endsection
