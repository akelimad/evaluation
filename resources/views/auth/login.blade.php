@extends('layouts.login')

@section('content')
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
        <p class="login-box-msg"> Veuillez entrer votre email et mot de passe pour vous connecter à l'application. </p>

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
                <input id="password" type="password" class="form-control input-lg" name="password" placeholder="Mot de passe" required="">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="">
            <div class="col-md-8">
              <div class="form-group">
                <input type="checkbox" id="remember" name="remember"/>
                <label for="remember"> Se souvenir de moi </label>
                <p><a href="{{ url('password/reset') }}"> Mot de passe oublié ? </a></p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg"> Se connecter</button>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
