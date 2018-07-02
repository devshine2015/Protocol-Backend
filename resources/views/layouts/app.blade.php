<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bridgit') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Bridgit') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="{{ route('login') }}">{{ __('Login') }}<span class="caret"></a>
                                <ul id="login-dp" class="dropdown-menu dropdown-menu-form">
                                    <li>
                                        <div class="navbar loginbar col-md-12">
                                            <div class="login-brand text-center">Bridgit</div>
                                        </div>
                                        <div class="row login-raw">
                                            <div class="col-md-12">
                                                <div class="social-buttons">
                                                    <button  class="btn btn-register active" id="register-form-link">Register</button>
                                                    <button  class="btn btn-signin" id="login-form-link"><i class="fa fa-twitter"></i> Signin</button>
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="register-form-data">
                                                <p class="hint">To start sharing with Bridgit, give us a little info.</p>
                                                 <form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="register-form">
                                                        <div class="form-group">
                                                             <label class="sr-only" for="exampleInputName2">Full name</label>
                                                             <input type="text" class="form-control" id="exampleInputName2" placeholder="Full name" required>
                                                        </div>
                                                        <div class="form-group">
                                                             <label class="sr-only" for="exampleInputEmail2">Email address</label>
                                                             <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" required>
                                                        </div>
                                                        <div class="form-group">
                                                             <label class="sr-only" for="exampleInputPassword2">Password</label>
                                                             <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
                                                        </div>
                                                        <div class="form-group mt-18">
                                                             <button type="submit" class="btn btn-primary">Register</button>
                                                              <a href="{{ url('api/login/google') }}" class="btn btn-primary social-submit">Sign in with Google</a>
                                                        </div>

                                                 </form>
                                                 <form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-form">
                                                        <div class="form-group">
                                                             <label class="sr-only" for="exampleInputEmail2">Email address</label>
                                                             <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Email address" required>
                                                        </div>
                                                        <div class="form-group">
                                                             <label class="sr-only" for="exampleInputPassword2">Password</label>
                                                             <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password" required>
                                                        </div>
                                                        <div class="form-group mt-18">
                                                             <button type="submit" class="btn btn-primary">Sign in</button>
                                                        </div>
                                                 </form>
                                            </div>
                                        </div>
                                       <div class="footer-login"><span>2017 © Bridgit | All Rights Reserved</span><br><a href="http://bridgit.io/" target="_blank" class="term-note">Terms &amp; Conditions</a><a href="http://bridgit.io/" target="_blank" class="term-note"> About Us</a></div>
                                    </li>
                                </ul>
                            </li>
                            {{-- <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li> --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

      {{--   <main class="py-4">
            @yield('content')
        </main> --}}
    </div>
<script>
$("document").ready(function() {

  $('.dropdown-menu').on('click', function(e) {
      if($(this).hasClass('dropdown-menu-form')) {
          e.stopPropagation();
      }
  });
});

$(function() {

$('#login-form-link').click(function(e) {
    $("#login-form").delay(100).fadeIn(100);
    $("#register-form").fadeOut(100);
    $("#register-form").css('display','none');
    $("#login-form").css('display','show');
    $('#register-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
});
$('#register-form-link').click(function(e) {
    $("#register-form").delay(100).fadeIn(100);
    $("#login-form").fadeOut(100);
    $("#register-form").css('display','show');
    $("#login-form").css('display','none');
    $('#login-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
});

});
</script>
</body>
</html>
