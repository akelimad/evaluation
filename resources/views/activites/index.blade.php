@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> Remplir votre évaluation pour: {{ $e->titre }} </h3>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}">Synthèse</a></li>
                            <li class="active"><a href="#">Evaluations</a></li>
                            <li><a href="">Carrieres</a></li>
                            <li><a href="">Formations</a></li>
                            <li><a href="">Competences</a></li>
                            <li ><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/objectifs')}}">Objectifs</a></li>
                            <li><a href="">Salaires</a></li>
                            <li><a href="">Commentaires</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="box-body mb40">
                                @if(count($user->children)>0)
                                    @include('questions/survey2')
                                @endif
                                @if(count($user->children)<=0)
                                    @include('questions/survey')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  