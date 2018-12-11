@extends('layouts.login')

@section('content')
<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-box-body ">
                    <div class="login-logo">
                        <img src="{{ asset('img/logo.png') }}" alt="" class="img-responsive">
                    </div>
                    @if ($errors->any())
              <div class="chm-alerts alert alert-danger alert-white rounded">
                            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
                            <div class="icon"><i class="fa fa-warning"></i></div>
                            <strong> Votre email et/ou mot de passe est invalide !! </strong></li>
                        </div>
              @endif
                    <p class="login-box-msg"> Veuillez entrer votre email et mot de passe pour se connecter à l'application. </p>
                    <form action="{{ url('/login') }}" method="post" class="form-horizontal" role="form">
                        {{ csrf_field() }}
                        <div class="">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" placeholder="Email" required="">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <input id="password" type="password" class="form-control input-lg" name="password" placeholder="Password" required="">
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="">
                          <div class="col-md-8">
                            <div class="form-group">
                                <label for="remember"> Se souvenir de moi </label>
                                <input type="checkbox" id="remember" name="remember"/>
                                <p>
                                    <a href="{{ url('password/reset') }}"> Mot de passe oublié ? </a>
                                </p>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block btn-lg"> Se connecter </button>
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          <!-- /.col -->
                        </div>
                    </form>
                    
                </div>
          </div>
        </div>
@endsection
