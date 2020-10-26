@extends('layouts.app')
@section('title', 'Tableau de board')
@section('breadcrumb')
  <li>Tableau de board</li>
@endsection
@section('content')
  <section class="content">
    <div class="card mb-30">
      <div class="card-header"><h3 class="m-0 p-0">{{ __("Effectif") }}</h3></div>
      <div class="card-body">
        <div class="row mb-0 mt-10">
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-purple-active m-0">
              <div class="inner">
                <h3>{{ $nbColls }}</h3>
                <p>{{ __("Collaborateurs") }}</p>
              </div>
              <div class="icon"><i class="fa fa-users"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 m-0">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ $nbMentors }}</h3>
                <p>{{ __("Managers") }}</p>
              </div>
              <div class="icon"><i class="fa fa-users"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 m-0">
            <div class="small-box bg-teal-gradient">
              <div class="inner">
                <h3>{{ $nbRHs }}</h3>
                <p>{{ __("RH") }}</p>
              </div>
              <div class="icon"><i class="fa fa-users"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 m-0">
            <div class="small-box bg-maroon-active">
              <div class="inner">
                <h3>{{ $nbrAdmins }}</h3>
                <p>{{ __("Admins") }}</p>
              </div>
              <div class="icon"><i class="fa fa-users"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-30">
      <div class="card-header"><h3 class="m-0 p-0">{{ __("Campagnes") }}</h3></div>
      <div class="card-body">
        <div class="row mb-0 mt-10">
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-gray-active m-0">
              <div class="inner">
                <h3> {{ $inProgress }} </h3>
                <p>{{ __("En cours") }}</p>
              </div>
              <div class="icon"><i class="fa fa-comments"></i></div>
              <a href="" class="small-box-footer"> </a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green-active m-0">
              <div class="inner">
                <h3> {{ $finished }} </h3>
                <p>{{ __("Fini") }}</p>
              </div>
              <div class="icon"><i class="fa fa-comments"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 m-0">
            <div class="small-box bg-red-active">
              <div class="inner">
                <h3>0</h3>
                <p>{{ __("Expiré") }}</p>
              </div>
              <div class="icon"><i class="fa fa-comments"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-orange-active">
              <div class="inner">
                <h3>{{ $taux }} %</h3>
                <p>{{ __("Taux de réalisation") }}</p>
              </div>
              <div class="icon"><i class="fa fa-comments"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  <script>
    // this to show popup message for ADMIN only after authentication
    $(window).on('load', function () {
      @if(\Auth::user()->hasRole('ADMIN') && session('popup'))
          setTimeout(function () {
        swal({
          title: "Bienvenue",
          text: "Bienvenue {{Auth::user()->name}} à votre espace d'administration",
          type: "success",
          allowOutsideClick: false
        }, {
          @php(session()->forget('popup'))
        });
      }, 1000)
      @endif

    });
  </script>
@endsection
