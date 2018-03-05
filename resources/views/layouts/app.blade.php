<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>E-entretiens | Tableau de board</title>
  <link rel="website" href="{{ url('/') }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ asset('bower_components/morris.js/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset('bower_components/jvectormap/jquery-jvectormap.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendor/iCheck/square/blue.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

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
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i" rel="stylesheet">

  <link href="{{ App\Asset::path('app.css') }}" rel="stylesheet">

</head>
<body class="hold-transition skin-blue sidebar-mini">
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
                  <img src="{{asset('img/avatar.png')}}" class="user-image" alt="User Image">
                  <span class="hidden-xs">{{ Auth::user()->name }} 
                    (@foreach(Auth::user()->roles as $role)
                        {{$role->name}}
                    @endforeach)</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                    <li class="user-header">
                    <img src="{{asset('img/avatar.png')}}" class="img-circle" alt="User Image">

                    <p>
                      {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                      <small> {{ Auth::user()->function }} </small>
                    </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                          <a href="{{url('/profile')}}" class="btn btn-info"><i class="fa fa-user"></i> Profile</a>
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
            <li>
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
            <a href="{{url('/')}}"><img src="{{ asset('img/logo1.png') }}" alt="" class="img-responsive"></a>
        </div>
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
              <img src="{{asset('img/avatar.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>{{ Auth::user()->name }}</p>
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
            <li class="active">
                <a href="{{url('/home')}}"><i class="fa fa-dashboard"></i> <span>Tableau de board</span></a>
            </li>

<!--             <li class="treeview">
                <a href="{{url('/')}}">
                    <i class="fa fa-comments"></i> <span>Mes entretiens</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('entretiens/evaluations') }}"><i class="fa fa-circle-o"></i> D'évaluation</a></li>
                    <li><a href="{{ url('entretiens/professionnels') }}"><i class="fa fa-circle-o"></i> Professionnels</a></li>
                </ul>
            </li> -->

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Mes collaborateurs</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('entretiens') }}"><i class="fa fa-circle-o"></i> Les entretiens</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Annoncer une formation</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Les rémunirations</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-graduation-cap"></i> <span>Formations</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href=""><i class="fa fa-circle-o"></i> Le catalogue </a></li>
                    <li><a href=""><i class="fa fa-circle-o"></i> Le planning </a></li>
                    <li><a href=""><i class="fa fa-circle-o"></i> Les candidats </a></li>
                    <li><a href=""><i class="fa fa-circle-o"></i> Les présences </a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-wrench"></i> <span>Configurations</span>
                  <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href=""><i class="fa fa-circle-o"></i> Abonnements </a></li>
                    <li><a href=""><i class="fa fa-circle-o"></i> Exporter </a></li>
                    <li><a href=""><i class="fa fa-circle-o"></i> Paramètres </a></li>
                    <li><a href=""><i class="fa fa-circle-o"></i> Importer des utilisateurs </a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i> <span>Administration</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('users') }}"><i class="fa fa-circle-o"></i> Les utilisateurs </a></li>
                    <li><a href="{{ url('users/import') }}"><i class="fa fa-circle-o"></i> Importer les utilisateurs </a></li>
                    <li><a href="{{ url('roles') }}"><i class="fa fa-circle-o"></i> Les rôles </a></li>
                    <li><a href="{{ url('permissions') }}"><i class="fa fa-circle-o"></i> Les permissions </a></li>
                    <li><a href="{{ url('entretiens/evaluations') }}"><i class="fa fa-circle-o"></i> Les évaluations </a></li>
                    <li class="treeview">
                      <a href="#"><i class="fa fa-help"></i> <span>Questionnaires</span>  
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="{{ url('survey') }}"><i class="fa fa-circle-o"></i> Questionnaire </a></li>
                        <li><a href="{{ url('survey2') }}"><i class="fa fa-circle-o"></i> Entretien d'evaluation </a></li>
                        <li><a href="{{ url('groupes') }}"><i class="fa fa-circle-o"></i> List groupes </a></li>
                        <li><a href="{{ url('questions/preview') }}"><i class="fa fa-circle-o"></i> List Questions </a></li>
                      </ul>
                    </li>
                </ul>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Tableau de board</li>
            </ol>
        </section>
        @yield('content')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; <script>document.write(new Date().getFullYear()) </script> </strong> All rights
        reserved.
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
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{asset('bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{asset('bower_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{asset('vendor/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('vendor/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.fr.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('js/demo.js')}}"></script>
<script src="{{asset('js/script.js')}}"></script>

<script src="{{ App\Asset::path('app.js') }}"></script>
</body>
</html>

