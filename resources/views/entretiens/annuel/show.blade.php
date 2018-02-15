@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="card box box-primary">
                    <h2 class="mb40"> Voir un entretien d'évaluation </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="{{url('entretiens/evaluations/'.$e->user->id)}}" >Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->type.'/'.$e->id.'/activites')}}" >Parcours</a></li>
                            <li><a href="#Compétences" >Compétences</a></li>
                            <li><a href="#Objectifs" >Objectifs</a></li>
                            <li><a href="#Formations" >Formations</a></li>
                            <li><a href="#Pénibilité" >Supports annexes</a></li>
                            <li><a href="#Documents" >Documents</a></li>
                            <li><a href="#Rémunérations" >Rémunérations</a></li>
                            <li><a href="#Notes" >Notes</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="Synthèse">
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
                                    <label class="control-label col-md-3">Titre  :</label>
                                    <div class="col-md-9"> {{ $e->titre }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Collaborateur(trice) évalué(e) : :</label>
                                    <div class="col-md-9"> {{ $e->user->name }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Société  :</label>
                                    <div class="col-md-9"> {{ $e->user->society }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Adresse email :  :</label>
                                    <div class="col-md-9"> {{ $e->user->email }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fonction   :</label>
                                    <div class="col-md-9"> {{ $e->user->function }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Service   :</label>
                                    <div class="col-md-9"> {{ $e->user->service }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <a href="" class="btn btn-default"> Annuler </a>
                                    <a href="" class="btn btn-primary"> Mettre à jour </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  