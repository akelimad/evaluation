@extends('layouts.login')

@section('content')
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="login-box-body card pl-30 pr-30">
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
        <p class="login-box-msg text-center mt-15 mb-15">Veuillez entrer votre email et mot de passe pour vous connecter à l'application.</p>

        <form action="{{ url('/login') }}" method="post" class="form-horizontal" role="form">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-12">
              <div class="has-feedback">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required="">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="has-feedback">
                <input id="password" type="password" class="form-control" name="password" placeholder="Mot de passe" required="">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="row mb-0">
            <div class="col-md-6">
              <div class="">
                <input type="checkbox" id="remember" name="remember"/>
                <label for="remember" class="d-inline-block">Se souvenir de moi</label>
                <p class="mb-10"><a href="{{ url('password/reset') }}">Mot de passe oublié ?</a></p>
              </div>
            </div>
            <div class="col-md-6">
              <button type="submit" class="btn btn-primary btn-block btn-lg">Se connecter</button>
            </div>
            <div class="clearfix"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
