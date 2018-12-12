<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Tableau de board | E-EVALUATION</title>
  <link rel="website" href="{{ url('/') }}">
  {{ csrf_field() }}
  <base href="{{ url('/') }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendor/iCheck/square/blue.css')}}">

  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.css')}}">

  <link rel="stylesheet" href="{{asset('css/alerts.css')}}">
  <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.css')}}?v={{ time() }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i" rel="stylesheet">

  <link href="{{ App\Asset::path('app.css') }}" rel="stylesheet">

  </head>
  <body class="hold-transition skin-blue sidebar-mini fixed">
    <div class="spinner-wp">
        <i class="fa fa-refresh fa-spin fa-5x" aria-hidden="true"></i>
        <p class="help-block"> Chargement ... </p>
    </div>
  <div class="wrapper">
      <header class="main-header">
      <!-- Logo -->
      <a href="{{url('/')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>M</b>E</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>E</b>-entretiens</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ Auth::user()->avatar ? asset('avatars/'.Auth::user()->avatar) : asset('img/avatar.png') }}" class="img-circle" alt="User Image" width="19">
                    <span class="hidden-xs">{{ Auth::user()->name." ".Auth::user()->last_name }} 
                      (@foreach(Auth::user()->roles as $role)
                          {{$role->name}}
                      @endforeach)</span>
                  </a>
                  <ul class="dropdown-menu">
                    <!-- User image -->
                      <li class="user-header">
                      <img src="{{ Auth::user()->avatar ? asset('avatars/'.Auth::user()->avatar) : asset('img/avatar.png') }}" class="img-circle" alt="User Image">

                      <p>
                        {{ Auth::user()->name." ".Auth::user()->last_name }}
                        @if( Auth::user()->email != "pca@pca.ma")
                        <small> {{ Auth::user()->function ? App\Setting::asList('society.functions', false, true)[Auth::user()->function] :'---' }} </small>
                        @endif
                      </p>
                      </li>
                      <!-- Menu Footer-->
                      <li class="user-footer">
                          <div class="pull-left">
                            <a href="{{url('/profile')}}" class="btn btn-info"><i class="fa fa-user"></i> Profil</a>
                          </div>
                          <div class="pull-right">
                            <a href="{{url('/logout')}}" class="btn btn-info">Déconnexion <i class="fa fa-sign-out"></i></a>
                          </div>
                      </li>
                  </ul>
              </li>
              <li>
                  <a href="{{url('logout')}}"><i class="fa fa-power-off"></i></a>
              </li>
              <!-- disable control sidebar skin -->
              <li style="display: none;">
                  <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
          </ul>
        </div>
      </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
          <div class="home-logo">
              <a href="{{url('/')}}">
                @if(App\Setting::findOne('society.logo')->value != '')
                <img src="{{ asset('logos/'.App\Setting::findOne('society.logo')->value) }}" alt="" class="img-responsive">
                @else
                <img src="{{ asset('img/logo.png') }}" alt="" class="img-responsive">
                @endif
              </a>
          </div>
          <!-- Sidebar user panel -->
          <div class="user-panel">
              <div class="pull-left image">
                <img src="{{ Auth::user()->avatar ? asset('avatars/'.Auth::user()->avatar) : asset('img/avatar.png') }}" class="img-circle" alt="User Image">
              </div>
              <div class="pull-left info">
                <p>{{ Auth::user()->name." ".Auth::user()->last_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
              </div>
          </div>
          <form action="#" method="get" class="sidebar-form">
              <div class="input-group">
                  <input type="text" name="q" class="form-control" placeholder="Recherche...">
                  <span class="input-group-btn">
                      <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                      </button>
                  </span>
              </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu" data-widget="tree">
              @role(["ADMIN", "RH"])
              <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Tableau de board</span></a>
              </li>
              @endrole
              @role(["RH", "MENTOR"])
              <li class="{{ Request::is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}"><i class="fa fa-users"></i> <span>Mes collaborateurs</span></a>
              </li>
              @endrole
              @role(["ADMIN"])
              <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{ url('users') }}"><i class="fa fa-users"></i> Utilisateurs</a></li>
              @endrole
              @role(["ADMIN", "RH"]) 
              <li class="{{ Request::is('entretiens/index') ? 'active' : '' }}"><a href="{{ url('entretiens/index') }}"><i class="fa fa-comments"></i> Entretiens</a></li>
              <li class="{{ Request::is('entretiens/evaluations') ? 'active' : '' }}"><a href="{{ url('entretiens/evaluations') }}"><i class="fa fa-pencil"></i> Evaluations en cours </a></li>
              <li class="{{ Request::is('entretiens/calendar') ? 'active' : '' }}"><a href="{{ url('entretiens/calendar') }}"><i class="fa fa-calendar"></i> Calendrier des entretiens</a></li>
              @endrole
              @role(["ADMIN"])
              <li class="treeview {{ Request::is('config*') ? 'active menu-open' : '' }}">
                <a href="#">
                  <i class="fa fa-gears"></i> <span>Configuration</span>
                  <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu" style="{{ Request::is('config*') ? 'display: block;' : '' }}">
                  <li class="{{ Request::is('config/settings') ? 'active' : '' }}">
                    <a href="{{ url('config/settings') }}"><i class="fa fa-wrench"></i> Réglages</a>
                  </li>
                  <li class="{{ Request::is('config/entretienObjectif') ? 'active' : '' }}">
                    <a href="{{ url('config/entretienObjectif') }}"><i class="fa fa-signal"></i> Objectifs </a>
                  </li>
                  <li class="{{ Request::is('config/skills') ? 'active' : '' }}">
                    <a href="{{ url('config/skills') }}"><i class="fa fa-graduation-cap"></i> Compétences </a>
                  </li>
                  <li class="{{ Request::is('config/surveys') ? 'active' : '' }}">
                    <a href="{{ url('config/surveys') }}"><i class="fa fa-question"></i> Quests. d'évaluation </a>
                  </li>
                  <li class="{{ Request::is('config/emails') ? 'active' : '' }}">
                    <a href="{{ url('config/emails') }}"><i class="fa fa-envelope"></i> Emails </a>
                  </li>
                  <li class="{{ Request::is('config/roles') ? 'active' : '' }}">
                    <a href="{{ url('config/roles') }}"><i class="fa fa-user"></i> Rôles </a>
                  </li>
                </ul>
              </li>
              @endrole
              @role(["ROOT"])
              <li>
                <a href="{{ url('crm') }}"><i class="fa fa-cog"></i> <span>Comptes des sociétés</span></a>
              </li>
              @endrole
          </ul>
      </section>
      <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
          <section class="content-header">
              <ol class="breadcrumb">
                  <li><a href="#"><i class="fa fa-home"></i> Accueil </a></li>
                  <li class="active">Tableau de board</li>
              </ol>
          </section>
          @yield('content')
      </div>
      <!-- /.content-wrapper -->
      <footer class="main-footer">
          <strong>Copyright &copy; <script>document.write(new Date().getFullYear()) </script> </strong> Lycom Tous droits
          reservés.
      </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Create the tabs -->
      <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li><a href="#control-sidebar-home-tab" data-toggle="tab" style="display: none;"><i class="fa fa-home"></i></a></li>

      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-home-tab">

        </div>
        <!-- /.tab-pane -->
        <!-- Stats tab content -->
        <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
        <!-- /.tab-pane -->

        <!-- /.tab-pane -->
      </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="{{asset('js/jquery.min.js')}}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{asset('js/jquery-ui.min.js')}}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{asset('js/bootstrap.min.js')}}"></script>
  <!-- daterangepicker -->
  <script src="{{asset('js/moment.min.js')}}"></script>
  <!-- datepicker -->
  <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{asset('js/bootstrap-datepicker.fr.min.js')}}"></script>
  <script src="{{asset('js/fullcalendar.min.js')}}"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="{{asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
  <!-- Slimscroll -->
  <script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>
  <script src="{{asset('js/chart.min.js')}}"></script>
  <!-- FastClick -->
  <script src="{{asset('js/fastclick.js')}}"></script>
  <script src="{{asset('js/select2.full.min.js')}}"></script>
  <script src="{{asset('vendor/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('js/adminlte.min.js')}}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{asset('js/pages/dashboard.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('js/demo.js')}}"></script>
  <script src="{{asset('js/sweetalert2.min.js')}}"></script>
  <script src="{{asset('js/script.js')}}?v={{ time() }}"></script>
  <script src="{{ App\Asset::path('app.js') }}"></script>
@yield('javascript')
</body>
</html>

