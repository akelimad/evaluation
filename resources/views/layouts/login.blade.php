<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> login | Mes entretiens </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('vendor/iCheck/square/blue.css')}}">

  <link rel="stylesheet" href="{{asset('css/alerts.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
	<div class="container">
	    <div class="row">
	        <div class="col-md-6 col-md-offset-3">
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
			    <div class="login-box-body ">
			      	<p class="login-box-msg"> Login pour pouvoir se connecter à l'application. </p>
			      	<form action="{{ url('/login') }}" method="post" class="form-horizontal" role="form">
			      		{{ csrf_field() }}
			      		<div class="">
			      			<div class="col-md-12">
						        <div class="form-group has-feedback">
						          <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" placeholder="Email" required="">
						          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
						        </div>
						    </div>
				    	</div>
				    	<div class="">
				    		<div class="col-md-12">
						        <div class="form-group has-feedback">
						          <input id="password" type="password" class="form-control input-lg" name="password" placeholder="Password" required="">
						          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
						        </div>
						    </div>
					    </div>
				        <div class="">
				          <div class="col-md-8">
				          	<div class="form-group">
					            <div class="checkbox icheck">
					              <label>
					                <input type="checkbox" name="remember"> Se souvenir de moi
					              </label>
					            </div>
					        </div>
				          </div>
				          <!-- /.col -->
				          <div class="col-md-4">
				          	<div class="form-group">
					            <button type="submit" class="btn btn-primary btn-block btn-flat"> Se connecter </button>
					        </div>
				          </div>
				          <!-- /.col -->
				        </div>
			      	</form>

			      	<a href="#"> Mot de passe oublié ? </a><br>
			    </div>
		  	</div>
		</div>
	</div>

<!-- jQuery 3 -->
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('vendor/iCheck/icheck.min.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
