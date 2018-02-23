@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="card box box-primary">
                    <h3 class="mb40"> Détails de l'entretien annuel d'évaluation: {{$e->titre}} </h3>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#" >Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/activites')}}" >Activités</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/skills')}}" >Compétences</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/objectifs')}}" >Objectifs</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/formations')}}" >Formations</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/documents')}}" >Documents</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/remunerations')}}">Rémunérations</a></li>
                            @if($e->type == "professionnel")
                            <li><a href="{{url('entretiens/'.$e->id.'/decisions')}}">Décisions</a></li>
                            @endif
                            <li><a href="{{url('entretiens/'.$e->id.'/comments')}}">Commentaire</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Date de l'entretien : </label>
                                    <div class="col-md-9"> {{ $e->date }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Entretien à clôturer avant fin : </label>
                                    <div class="col-md-9"> {{ $e->date }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Validé par le collaborateur :</label>
                                    <div class="col-md-9"> <span class="label label-success">oui</span> </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Validé par le mentor :</label>
                                    <div class="col-md-9"> <span class="label label-success">oui</span> </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Mentor :</label>
                                    <div class="col-md-9"> mentor </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Titre :</label>
                                    <div class="col-md-9"> {{ $e->titre }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Collaborateur(trice) évalué(e) :</label>
                                    <div class="col-md-9"> {{ $e->user->name }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Société :</label>
                                    <div class="col-md-9"> {{ $e->user->society ? $e->user->society : '--'  }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Adresse email :</label>
                                    <div class="col-md-9"> {{ $e->user->email }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fonction :</label>
                                    <div class="col-md-9"> {{ $e->user->function ? $e->user->function : '--' }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Service :</label>
                                    <div class="col-md-9"> {{ $e->user->service ? $e->user->service : '--' }} </div>
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
  