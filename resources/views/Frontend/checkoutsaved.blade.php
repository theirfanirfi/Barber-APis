@extends('Frontend.MasterLayout')
@section('content')
            <!-- ========================================= CONTENT ========================================= -->
            <section id="checkout-page">
                    <div class="container">
                        <div class="col-xs-12 no-margin">

                            <div class="billing-address">
                                <h2 class="border h1">billing address</h2>
                                <form action="{{ route('placecheckoutorder') }}" method="post">
                                    <div class="row field-row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label>Full name*</label>
                                            <input class="le-input" name="name" value="{{ $checkout->firstname }}">
                                        </div>
                                    </div><!-- /.field-row -->
                                    @csrf
                                    <input type="hidden" name="checkout_id" value="{{ $checkout->chk_id }}" />

                                    <div class="row field-row">
                                        <div class="col-xs-12">
                                            <label>company name</label>
                                            <input class="le-input" name="company" value="{{ $checkout->company }}" >
                                        </div>
                                    </div><!-- /.field-row -->

                                    <div class="row field-row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label>address*</label>
                                            <input class="le-input" data-placeholder="street address" name="address" value="{{ $checkout->address }}" >
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <label>&nbsp;</label>
                                            <input class="le-input" data-placeholder="town" name="citytown"  value="{{ $checkout->town }}">
                                        </div>
                                    </div><!-- /.field-row -->

                                    <div class="row field-row">
                                        <div class="col-xs-12 col-sm-4">
                                            <label>postcode / Zip*</label>
                                            <input class="le-input" name="postcode"  value="{{ $checkout->postalcode }}">
                                        </div>
                                        <div class="col-xs-12 col-sm-4">
                                            <label>email address*</label>
                                            <input class="le-input" name="email"  value="{{ $checkout->email }}">
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <label>phone number*</label>
                                            <input class="le-input" name="phone"  value="{{ $checkout->phonenumber }}">
                                        </div>
                                    </div><!-- /.field-row -->



                            </div><!-- /.billing-address -->



                            <section id="your-order">
                                <h2 class="border h1">your order</h2>

                                        @if(!empty($products))
                                        @foreach ($products as $p)
                                    <div class="row no-margin order-item">
                                        <div class="col-xs-12 col-sm-1 no-margin">
                                            <a href="{{ route('deleteproductsavedincart',['id' => $p->product_id]) }}" class="qty">{{  $p->quantity_ordered }} X</a>
                                        </div>

                                        <div class="col-xs-12 col-sm-9 ">
                                            <div class="title"><a href="{{ route('product',['id' => $p->product_id]) }}">{{ $p->product_name }} </a></div>
                                            {{-- <div class="brand">{{ $p->getCat() }}</div> --}}
                                        </div>

                                        <div class="col-xs-12 col-sm-2 no-margin">
                                            <div class="price">   ${{ $p->product_price * $p->products_quantity }}</div>
                                        </div>
                                    </div><!-- /.order-item -->
                                    @endforeach
                                    @endif

                            </section><!-- /#your-order -->

                            <div id="total-area" class="row no-margin">
                                <div class="col-xs-12 col-lg-4 col-lg-offset-8 no-margin-right">
                                    <div id="subtotal-holder">
                                        <ul class="tabled-data inverse-bold no-border">
                                            <li>
                                                <label>cart subtotal</label>
                                                <div class="value ">${{ $checkout->total_price }}</div>
                                            </li>
                                         <!--   <li>
                                                <label>shipping</label>
                                                <div class="value">
                                                    <div class="radio-group">
                                                        <input class="le-radio" type="radio" name="group1" value="free"> <div class="radio-label bold">free shipping</div><br>
                                                        <input class="le-radio" type="radio" name="group1" value="local" checked>  <div class="radio-label">local delivery<br><span class="bold">$15</span></div>
                                                    </div>
                                                </div>
                                            </li> -->
                                        </ul><!-- /.tabled-data -->

                                        <ul id="total-field" class="tabled-data inverse-bold ">
                                            <li>
                                                <label>order total</label>
                                                <div class="value">${{ $checkout->total_price }}</div>
                                            </li>
                                        </ul><!-- /.tabled-data -->

                                    </div><!-- /#subtotal-holder -->
                                </div><!-- /.col -->
                            </div><!-- /#total-area -->

                            <div id="payment-method-options">


                                    <div class="payment-method-option">
                                        <input class="le-radio" type="radio" name="group2" value="paypal" checked>
                                        <div class="radio-label bold ">paypal system (payment method)</div>
                                    </div><!-- /.payment-method-option -->

                            </div><!-- /#payment-method-options -->

                            <div class="place-order-button">
                                <button type="submit" class="le-button big">place order</button>
                            </form>

                            </div><!-- /.place-order-button -->

                        </div><!-- /.col -->
                    </div><!-- /.container -->
                </section><!-- /#checkout-page -->
                <!-- ========================================= CONTENT : END ========================================= -->

@endsection
