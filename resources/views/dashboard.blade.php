@extends('layouts.app')
@section('title', 'Tableau de board')
@section('breadcrumb')
  <li>Tableau de board</li>
@endsection
@section('content')
  <section class="content p-xxs-10">
    <div class="card mb-30">
      <div class="card-header"><h3 class="m-0 p-0">{{ __("Effectif") }}</h3></div>
      <div class="card-body">
        <div class="row mb-0">
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-purple-active m-0">
              <div class="inner">
                <h3>{{ $nbColls }}</h3>
                <p>{{ __("Collaborateurs") }}</p>
              </div>
              <div class="icon"><i class="fa fa-users"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-primary m-0">
              <div class="inner">
                <h3>{{ $nbMentors }}</h3>
                <p>{{ __("Managers") }}</p>
              </div>
              <div class="icon"><i class="fa fa-users"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-teal-gradient m-0">
              <div class="inner">
                <h3>{{ $nbRHs }}</h3>
                <p>{{ __("RH") }}</p>
              </div>
              <div class="icon"><i class="fa fa-users"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-maroon-active m-0">
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
        <div class="row mb-0">
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-gray-active m-0">
              <div class="inner">
                <h3>{{ $countCurrentCampaigns }}</h3>
                <p>{{ __("En cours") }}</p>
              </div>
              <div class="icon"><i class="fa fa-comments"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-green-active m-0">
              <div class="inner">
                <h3>{{ $countFinishedCampaigns }}</h3>
                <p>{{ __("Fini") }}</p>
              </div>
              <div class="icon"><i class="fa fa-comments"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-red-active mb-0">
              <div class="inner">
                <h3>{{ $countExpiredCampaigns }}</h3>
                <p>{{ __("Expiré") }}</p>
              </div>
              <div class="icon"><i class="fa fa-comments"></i></div>
              <a href="" class="small-box-footer"></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6 mb-sm-20 mb-md-20">
            <div class="small-box bg-orange-active mb-0">
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
