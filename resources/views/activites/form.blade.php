@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ajouter / Modifier une activité</h3>
                        </div>
                        <form class="form-horizontal" action="{{url('entretiens/activites/store')}}" method="post">
                            <input type="hidden" name="id" value="{{ isset($a->id) ? $a->id : null }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="titre" class="col-md-2 control-label">Activité</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="titre" id="titre" placeholder="" value="{{isset($a->titre) ? $a->titre :''}}" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="client" class="col-md-2 control-label">Client</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="client" id="client" placeholder="" value="{{isset($a->client) ? $a->client :''}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="duree" class="col-md-2 control-label">Durée</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="duree" id="duree" placeholder="" value="{{isset($a->duree) ? $a->duree :''}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="acquisition" class="col-md-2 control-label">Acquisition</label>
                                    <div class="col-md-10">
                                        <textarea name="acquisition" id="acquisition" class="form-control">{{ isset($a->acquisition) ? $a->acquisition :''}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="amelioration" class="col-md-2 control-label">Points d'amélioration</label>
                                    <div class="col-md-10">
                                        <textarea name="amelioration" id="amelioration" class="form-control">{{ isset($a->amelioration) ? $a->amelioration :''}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="commentaire" class="col-md-2 control-label">Commentaire</label>
                                    <div class="col-md-10">
                                        <textarea name="commentaire" id="commentaire" class="form-control">{{ isset($a->commentaire) ? $a->commentaire :''}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="evaluation" class="col-md-2 control-label">Evaluation</label>
                                    <div class="col-md-10">
                                        <select name="evaluation" id="evaluation" class="form-control">
                                            <option value=""> === select ===  </option>
                                            <option value="1" {{isset($a->evaluation) && $a->evaluation == "1" ? 'selected' :''}}> Insatisfaisant  </option>
                                            <option value="2" {{isset($a->evaluation) && $a->evaluation == "2" ? 'selected' :''}}> Peu satisfaisant  </option>
                                            <option value="3" {{isset($a->evaluation) && $a->evaluation == "3" ? 'selected' :''}}> Satisfaisant   </option>
                                            <option value="4" {{isset($a->evaluation) && $a->evaluation == "4" ? 'selected' :''}}> Assez satisfaisant  </option>
                                            <option value="5" {{isset($a->evaluation) && $a->evaluation == "5" ? 'selected' :''}}> Très satisfaisant  </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Annuler</button>
                                <button type="submit" class="btn btn-info pull-right">Sauvegarder</button>
                            </div>
                        </form>
                      </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  