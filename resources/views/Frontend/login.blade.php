@extends('Frontend.MasterLayout')
@section('content')
            <!-- ========================================= MAIN ========================================= -->
            <main id="authentication" class="inner-bottom-md">
                <div class="container">
                    <div class="row">

                        <div class="col-md-6">
                            <section class="section sign-in inner-right-xs">
                                <h2 class="bordered">Sign In</h2>

                                <form role="form" class="login-form cf-style-1" action="{{ route('loginPost') }}" method="POST">
                                    <div class="field-row">
                                        <label>Email</label>
                                        <input type="email" class="le-input" name="email">
                                    </div><!-- /.field-row -->
                                    @csrf
                                    <div class="field-row">
                                        <label>Password</label>
                                        <input type="password" class="le-input" name="password">
                                    </div><!-- /.field-row -->

                                    <div class="field-row clearfix">
                                       <!--  <span class="pull-left">
                                            <label class="content-color"><input type="checkbox" class="le-checbox auto-width inline"> <span class="bold">Remember me</span></label>
                                        </span>
                                       <span class="pull-right">
                                            <a href="#" class="content-color bold">Forgotten Password ?</a>
                                        </span> -->
                                    </div>

                                    <div class="buttons-holder">
                                        <button type="submit" class="le-button huge">Sign In</button>
                                    </div><!-- /.buttons-holder -->
                                </form><!-- /.cf-style-1 -->

                            </section><!-- /.sign-in -->
                        </div><!-- /.col -->

                        <div class="col-md-6">
                            <section class="section register inner-left-xs">
                                <h2 class="bordered">Create New Account</h2>

                                <form role="form" class="register-form cf-style-1" action="{{ route('register') }}" method="POST">
                                    <div class="field-row">
                                        <label>Full Name</label>
                                        <input type="text" class="le-input" name="name">
                                    </div><!-- /.field-row -->

                                    <div class="field-row">
                                        <label>Email</label>
                                        <input type="email" class="le-input" name="email">
                                    </div><!-- /.field-row -->
                                    @csrf
                                    <div class="field-row">
                                        <label>Password</label>
                                        <input type="password" class="le-input" name="password">
                                    </div><!-- /.field-row -->

                                    <div class="buttons-holder">
                                        <button type="submit" class="le-button huge">Sign Up</button>
                                    </div><!-- /.buttons-holder -->
                                </form>

                            </section><!-- /.register -->

                        </div><!-- /.col -->

                    </div><!-- /.row -->
                </div><!-- /.container -->
            </main><!-- /.authentication -->
            <!-- ========================================= MAIN : END ========================================= -->

@endsection
