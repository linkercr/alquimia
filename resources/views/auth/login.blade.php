
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login SIF by Linkercr</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png')}}">
    <link href="{{ asset('css/style.css')}}" rel="stylesheet">
    <!-- Styles -->
    <style>

        #particles-js {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body class="h-100">

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->





    <div class="login-form-bg h-100">
        <div class="container h-100"><div id="particles-js"></div>
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <a class="text-center" href="index.html"> <h4>SIF Login </h4></a>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div><br />
                                @endif
                                <form class="mt-5 mb-5 login-input" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                                          @error('email')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                              <script>
                                          @enderror
                                    </div>
                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                          @error('password')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="form-group col-md-4">
                                               <div class="captcha">
                                                 <span>{!! captcha_img() !!}</span>
                                                 <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                                                 </div>
                                              </div>
                                          </div>
                                    <div class="row">
                                            <div class="col-md-4"></div>
                                              <div class="form-group col-md-4">
                                               <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"></div>
                                            </div>
                                            <button type="submit" class="btn login-form__btn submit w-100">
                                                {{ __('Login') }}
                                            </button>

                                              <div class="pagination justify-content-end">   <!--**********************************
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                              ***********************************-->
                                            </div>

                                </form>
                                <!--**********************************
                                <p class="mt-5 login-form__footer">Dont have account? <a href="{{ route('register') }}" class="text-primary">Sign Up</a> now</p>
                                  ***********************************-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $('#refresh').click(function(){
          $.ajax({
             type:'GET',
             url:'refreshcaptcha',
             success:function(data){
                $(".captcha span").html(data.captcha);
             }
          });
        });
        </script>


    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('plugins/common/common.min.js')}}"></script>
    <script src="{{ asset('js/custom.min.js')}}"></script>
    <script src="{{ asset('js/settings.js')}}"></script>
    <script src="{{ asset('js/gleek.js')}}"></script>
    <script src="{{ asset('js/styleSwitcher.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
                /* particlesJS.load(@dom-id, @path-json, @callback (optional)); */
                particlesJS.load('particles-js', 'js/particlesjs-config.json', function() {

                });
            </script>
</body>
</html>
