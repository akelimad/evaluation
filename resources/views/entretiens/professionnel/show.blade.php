@extends('layouts.app')
@section('content')
    <section class="content professionels">
        <div class="row">
            <div class="col-md-12">
                <div class="card box box-primary">
                    <h3 class="mb40"> Détails de l'entretien professionnel: {{$e->titre}} </h3>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#">Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/activites')}}">Activités</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/formations')}}">Formations</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/documents')}}">Documents</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/decisions')}}">Décisions</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/comments')}}">Commentaire</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Crée le </label>
                                    <div class="col-md-9"> {{ $e->date }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Limité au </label>
                                    <div class="col-md-9"> {{ $e->date }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Titre </label>
                                    <div class="col-md-9"> {{ $e->titre }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Motif </label>
                                    <div class="col-md-9"> {{ $e->motif }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fréquence </label>
                                    <div class="col-md-9">
                                        @if($e->frequence == 1) Tous les 6 ans 
                                        @elseif($e->frequence == 2) Tous les 2 ans 
                                        @elseif($e->frequence == 3) Autre cas
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Synthèse du collaborateur </label>
                                    <div class="col-md-9"> {{ $e->conclusion_coll }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Synthèse du mentor </label>
                                    <div class="col-md-9"> {{ $e->conclusion_mentor }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <a href="{{url('/')}}" class="btn btn-default"> Annuler </a>
                                    <a onclick="return chmEntretien.edit({e_id:{{ $e->id }}})" class="btn btn-success"> Mettre à jour </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  