@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <h3> Entretiens en cours </h3>
                        <hr>
                        @forelse ($collaborateurs as $coll)
                        <div class="current-interview">
                            @if(!empty($coll->avatar))
                                <img src="{{ asset('avatar/'.$coll->avatar) }}" class="img-responsive img-circle" width="40" alt="">
                            @else
                                <img src="{{ asset('img/avatar.png') }}" class="img-responsive img-circle" width="40" alt="">
                            @endif
                            <span class="name"> {{ $coll->name }} {{ $coll->last_name }} </span>
                            <div class="clearfix"></div>
                            <div class="coll-entretien">
                                <p> 1 Entretien annuel </p>
                                <p> 1 Entretien professionnel </p>
                            </div>
                        </div>
                        @empty
                            <div class="current-interview">
                                Aucun entretien en cours
                            </div>
                        @endforelse 
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card portlet box box-primary interview-pro">
                    <h3> Historique d'un collaborateur </h3>
                    <div class="mb40">
                        <hr>
                        <h2>Les entretiens d'évaluation</h2>
                        <p class="help-block">Veuillez sélectionner un collaboreur dans la liste de gauche ou créer un nouvel entretien d'évaluation... </p>
                        <a href="{{ url('entretiens/evaluation/create') }}" class="label label-success"> Créer un entretien d'évaluation </a>
                    </div>
                    <div class="">
                        <h2>Les entretiens professionnels</h2>
                        <p class="help-block">Veuillez sélectionner un collaboreur dans la liste de gauche ou créer une nouvelle évaluation...  </p>
                        <a href="{{ url('entretiens/professionnel/create') }}" class="label label-success"> Créer un entretien professionnel </a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  