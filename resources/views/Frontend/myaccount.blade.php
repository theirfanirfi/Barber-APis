@extends('Frontend.MasterLayout')
@section('content')
<div id="products-tab" class="wow fadeInUp">
        <div class="container">
            <div class="tab-holder">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" >
                    <li class="active"><a href="#featured" data-toggle="tab">My Account</a></li>
                    <li><a href="#new-arrivals" data-toggle="tab">Paid Checkouts</a></li>
                    <li><a href="#unpaid" data-toggle="tab">UnPaid Checkouts</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="featured">
                        <div class="product-grid-holder">
                            <div class="row" style="margin:20px;">
                                <div class="col-md-6">
                                        <form action="{{ route('updateaccount') }}" method="POST">
                                            <p>
                                            <label>Full name: </label>
                                                <input type="text" name="name" value="{{ $user->name }}" class="form-control" />
                                            </p>
                                            @csrf
                                            <p>
                                                    <label>Email: </label>
                                                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" />
                                                    </p>




                                        <button type="submit" class="btn btn-primary">Update Account</button>
                                        </form>
                                </div>

                                <div class="col-md-6">
                                    <form action="{{ route('changepassword') }}" method="POST">
                                    <p>
                                        @csrf
                                                <label>Current Password: </label>
                                                    <input type="password" name="current_pass" class="form-control" />

                                                    <label>New Password: </label>
                                                    <input type="password" name="new_pass" class="form-control" />
                                                </p>

                                                <button type="submit" class="btn btn-primary">Change Password</button>
                                    </form>

                                </div>
                            </div>


                        </div>


                    </div>


                    <div class="tab-pane" id="new-arrivals">
                        <div class="product-grid-holder">
            <!-- ========================================= CONTENT ========================================= -->
            <div class="col-xs-12 col-md-9 items-holder no-margin">
                    @if(!empty($paid))
                    <table class="table table-responsive" style="margin:20px;">

                        <th>Products</th>
                        <th>Created on</th>
                        <th>Total Price</th>
                        {{-- <th>Delete</th> --}}
                    @foreach ($paid->get() as $p)

                        <tr>
                            <td> {{  $p->products_quantity }} | <a href="{{ route('savedcart',['id' => $p->id]) }}">View</a></td>
                            <td>{{ $p->created_at }}</td>
                            <td>${{ $p->total_price }}</td>
                            {{-- <td>Delete</td> --}}
                        </tr>
                    @endforeach

                    </table>

                    @endif

                </div>
                <!-- ========================================= CONTENT : END ========================================= -->

                        </div>


                    </div>


                    <div class="tab-pane" id="unpaid">
                            <div class="product-grid-holder">
                                    @if(!empty($unpaid))
                                    <table class="table table-responsive" style="margin:20px;">

                                        <th>Products</th>
                                        <th>Created on</th>
                                        <th>Total Price</th>
                                        <th>Checkout</th>
                                        <th>Delete</th>
                                    @foreach ($unpaid->get() as $p)

                                        <tr>
                                            <td>{{ $p->products_quantity }} | <a href="{{ route('savedcart',['id' => $p->id]) }}">View</a></td>
                                            <td>{{ $p->created_at }}</td>
                                            <td>${{ $p->total_price }}</td>
                                            <td>${{ $p->total_price }}</td>
                                            <td><a href="{{ route('deletesavedcheckout',['id' => $p->id]) }}">Delete</a></td>
                                        </tr>
                                    @endforeach

                                    </table>

                                    @endif

                            </div>


                        </div>

                </div>
            </div>
        </div>
    </div>


@include('Frontend.includes.recentlyviewed')

@endsection
