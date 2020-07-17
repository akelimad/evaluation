@extends('layouts.app')
@section('title', 'Tableau de board')
@section('breadcrumb')
  <li>Tableau de board</li>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3> {{ $inProgress }} </h3>
            <p>Campages en cours</p>
          </div>
          <div class="icon"><i class="fa fa-comments"></i></div>
          <a href="" class="small-box-footer"> </a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3> {{ $finished }} </h3>
            <p>Campages terminées</p>
          </div>
          <div class="icon"><i class="fa fa-comments"></i></div>
          <a href="" class="small-box-footer"></a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{ $nbColls }}</h3>
            <p>Collaborateurs</p>
          </div>
          <div class="icon"><i class="fa fa-users"></i></div>
          <a href="" class="small-box-footer"></a>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{ $nbMentors }}</h3>
            <p>Managers</p>
          </div>
          <div class="icon"><i class="fa fa-users"></i></div>
          <a href="" class="small-box-footer"></a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-navy">
          <div class="inner">
            <h3>{{ $taux }} %</h3>
            <p>Taux de réalisations des campagnes</p>
          </div>
          <div class="icon"><i class="fa fa-users"></i></div>
          <a href="" class="small-box-footer"></a>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </section>
@endsection

@section('javascript')
  <script>

  </script>
@endsection
