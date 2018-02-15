@extends('layouts.app')
@section('content')
    <section class="content professionels">
        <div class="row">
            <div class="col-md-12">
                <div class="card box box-primary">
                    <h2 class="mb40"> Voir un entretien professionel </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#Synthèse" data-toggle="tab">Synthèse</a></li>
                            <li><a href="#Parcours" data-toggle="tab">Parcours</a></li>
                            <li><a href="#Formations" data-toggle="tab">Formations</a></li>
                            <li><a href="#Supports_annexes" data-toggle="tab">Supports annexes</a></li>
                            <li><a href="#Décisions" data-toggle="tab">Décisions</a></li>
                            <li><a href="#Notes" data-toggle="tab">Notes</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="Synthèse">
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
                                    <div class="col-md-9"> {{ $e->frequence }} </div>
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
                                    <a href="" class="btn btn-default"> Annuler </a>
                                    <a href="" class="btn btn-primary"> Mettre à jour </a>
                                </div>
                            </div>
                            <div class="tab-pane" id="Parcours">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fonction</label>
                                    <div class="col-md-9">uio</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Service</label>
                                    <div class="col-md-9">uio</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="Formations">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Afficher l'aide contextuelle</label>
                                    <div class="col-md-9"><span class="label label-success pull-right">Oui</span></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="Supports_annexes">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Afficher l'aide contextuelle</label>
                                    <div class="col-md-9"><span class="label label-success pull-right">Oui</span></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="Décisions">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Afficher l'aide contextuelle</label>
                                    <div class="col-md-9"><span class="label label-success pull-right">Oui</span></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="Notes">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Afficher l'aide contextuelle</label>
                                    <div class="col-md-9"><span class="label label-success pull-right">Oui</span></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  