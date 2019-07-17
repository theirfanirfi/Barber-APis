<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="keywords" content="MediaCenter, Template, eCommerce">
        <meta name="robots" content="all">

        <title>TechGiantz</title>

        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">

        <!-- Customizable CSS -->
        <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/colors/green.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/owl.carousel.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/owl.transitions.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/animate.min.css') }}">

        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>

        <!-- Icons/Glyphs -->
        <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('mages/favicon.ico') }}">

        <!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
        <!--[if lt IE 9]>
            <script src="{{ URL::asset('js/html5shiv.js') }}"></script>
            <script src="{{ URL::asset('js/respond.min.js') }}"></script>
        <![endif]-->
    </head>

    <body>
        <div class="wrapper">
<!-- ============================================================= TOP NAVIGATION ============================================================= -->
            <nav class="top-bar animate-dropdown">
                <div class="container">
                    <div class="col-xs-12 col-sm-6 no-margin">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="#">Contact</a></li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="">Categories</a>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach ($cats as $c)
                                    <li><a href="{{ route('category',['id' => $c->cat_id]) }}">{{ $c->cat_title }}</a></li>
                                    @endforeach
                                                        </ul>
                            </li>
                        </ul>
                    </div><!-- /.col -->

                    <div class="col-xs-12 col-sm-6 no-margin">
                        <ul class="right">
                            @if(!Auth::check())
                            <li><a href="{{ route('login') }}">Register</a></li>
                            <li><a href="{{ route('login') }}">Login</a></li>
                            @else
                            <li><a href="{{ route('account') }}">My Account</a></li>
                            <li><a href="{{ route('logout') }}">Logout</a></li>
                            @endif
                        </ul>
                    </div><!-- /.col -->
                </div><!-- /.container -->
            </nav><!-- /.top-bar -->
            <!-- ============================================================= TOP NAVIGATION : END ============================================================= -->
