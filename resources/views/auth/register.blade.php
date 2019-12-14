<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register SIF by Linkercr</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <link rel="stylesheet" href="fontawesome/releases/v5.5.0/css/all.css">
    <link href="css/style.css" rel="stylesheet">

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
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">

                                    <a class="text-center" href="index.html"> <h4>{{ __('Register') }}</h4></a>

                                <form method="POST" class="mt-5 mb-5 login-input" action="{{ route('register') }}">
                                  @csrf
                                  <div class="form-row">
                                        <div class="form-group col-md-6">
                                                {{ Form::label('name', 'Name') }}
                                                {{ Form::text('name', '', array('class' => 'form-control')) }}
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                                <label>Tipo de Identificacion</label>
                                                <select class="form-control form-control-sm"  name="tipo_identificacion">
                                                        <option ></option>
                                                        <option value="1">Cédula</option>
                                                        <option value="2">DIMEX</option>
                                                        <option value="3">Pasaporte</option>
                                                    </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                            {{ Form::label('name', 'Número de Indentificación') }}
                                            {{ Form::text('identificacion', '', array('class' => 'form-control')) }}
                                    </div>
                                    <div class="form-row">
                                            <div class="form-group col-md-6">
                                                    <label>Tipo de Telefono</label>
                                                    <select class="form-control form-control-sm"  name="tipo_telefono">
                                                            <option ></option>
                                                            <option value="1">Celular</option>
                                                            <option value="2">Fijo Oficina</option>
                                                            <option value="3">Fijo Casa</option>
                                                        </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    {{ Form::label('name', 'Número de Telefono') }}
                                                    {{ Form::number('telefono', '', array('class' => 'form-control')) }}
                                            </div>
                                        </div>

                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="{{ __('E-Mail Address') }}" >
                                          @error('email')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror
                                    </div>

                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}" required>
                                          @error('password')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror
                                    </div>
                                    <div class="form-group">
                                        <input id="password-confirm" type="password" class="form-control " name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}" required>
                                    </div>
                                    <button type="submit" class="btn login-form__btn submit w-100">{{ __('Register') }}</button>
                                </form>
                                    <p class="mt-5 login-form__footer">Have account <a href="{{ route('login') }}" class="text-primary">Sign Up </a> now</p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!--**********************************
        Scripts
    ***********************************-->
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
</body>
</html>
