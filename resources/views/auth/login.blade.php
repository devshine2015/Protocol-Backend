<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="">{{ __('Login') }}<span class="caret"></a>
<ul id="login-dp" class="dropdown-menu dropdown-menu-form">
    <li>
        <div class="navbar loginbar col-md-12">
            <div class="login-brand text-center">Bridgit</div>
        </div>
        <div class="row login-raw">
            <div class="col-md-12">
                <div class="social-buttons">
                    <button  class="btn btn-register active" id="register-form-link">Register</button>
                    <button  class="btn btn-signin" id="login-form-link"> Signin</button>
                </div>
            </div>
            <div class="col-md-12" id="register-form-data">
                <p class="hint">To start sharing with Bridgit, give us a little info.</p>
                 <form class="form" role="form" method="post" action="{{ route('register') }}" accept-charset="UTF-8" id="register-form">
                  {!! csrf_field() !!}
                        <div class="form-group">
                             <label class="sr-only" for="exampleInputName2">Full name</label>
                             <input type="text" class="form-control" id="exampleInputName2" name="name" placeholder="Full name" required>
                        </div>
                        <div class="form-group">
                             <label class="sr-only" for="exampleInputEmail2">Email address</label>
                             <input type="email" class="form-control" id="exampleInputEmail2" name="email" placeholder="Email address" required>
                        </div>
                        <div class="form-group">
                             <label class="sr-only" for="exampleInputPassword2">Password</label>
                             <input type="password" name="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
                        </div>
                        <div class="form-group mt-18">
                             <button type="submit" class="btn btn-primary">Register</button>
                              <a href="{{ url('weblogin/google') }}" class="btn btn-primary social-submit">Sign in with Google</a>
                        </div>

                 </form>
                 <form class="form" role="form" method="post" action="{{ route('login') }}" accept-charset="UTF-8" id="login-form">
                    {!! csrf_field() !!}
                        <div class="form-group">
                             <label class="sr-only" for="exampleInputEmail2">Email address</label>
                             <input type="email" class="form-control" id="exampleInputEmail3" name="email" placeholder="Email address" required>
                        </div>
                        <div class="form-group">
                             <label class="sr-only" for="exampleInputPassword2">Password</label>
                             <input type="password" class="form-control" id="exampleInputPassword3" name="password" placeholder="Password" required>
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