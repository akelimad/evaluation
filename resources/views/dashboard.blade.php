@extends('layouts.app')

@section('content')
<section class="content">
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>15</h3>
                <p>Nombre d'entretiens en cours</p>
            </div>
            <div class="icon"><i class="fa fa-comments"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>3</h3>
                <p>Entretiens terminés</p>
            </div>
            <div class="icon"><i class="fa fa-comments"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $nbColls }}</h3>
                <p>Nombre de Collaborateurs</p>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $nbMentors }}</h3>
                <p>Nombre de Mentors</p>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
</section>
@endsection
