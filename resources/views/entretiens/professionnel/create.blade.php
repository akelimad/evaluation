@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ajouter un entretien professionnel</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" action="{{ url('entretiens/store') }}">
                            <input type="hidden" name="type" value="professionnel">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="date" class="control-label">Date de l'entretien</label>
                                    <input type="text" name="date" class="form-control" id="datepicker" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="titre" class="control-label">Titre</label>
                                    <input type="text" name="titre" class="form-control" id="titre" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="motif" class="control-label">Motif</label>
                                    <select id="motif" name="motif" class="form-control">
                                        <option value="obligatoire">Entretien professionnel obligatoire</option>
                                        <option value="conge_parental">Reprise d'activité suite à un congé parental</option>
                                        <option value="conge_education">Reprise d'activité suite à un congé parental d'éducation</option>
                                        <option value="conge_adoption">Reprise d'activité suite à un congé d'adoption</option>
                                        <option value="conge_sabbatique">Reprise d'activité suite à un congé sabbatique</option>
                                        <option value="mobilite_volontaire">Reprise d'activité suite à une période de mobilité volontaire sécurisée</option>
                                        <option value="arret_maladie">Reprise d'activité suite à un arrêt longue maladie</option>
                                        <option value="mandat_syndical">Reprise d'activité suite à un mandat syndical</option>
                                        <option value="autre">Reprise d'activité suite à un autre cas</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="control-label">Fréquence</label>
                                    <select name="frequence" id="frequence" class="form-control">
                                        <option value="deux_ans">Tous les deux ans</option>
                                        <option value="six_ans">Tous les six ans</option>
                                        <option value="autre">Autre cas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="conclusion_mentor" class="control-label">Conclusion du mentor</label>
                                    <textarea name="conclusion_mentor" id="conclusion_mentor" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6"> 
                                    <label for="conclusion_coll" class="control-label">Conclusion du collaborateur</label>
                                    <textarea name="conclusion_coll" id="conclusion_coll" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="user_id" class="control-label">Collaborateur à evaluer</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"> {{ $user->email }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="box-footer">
                                <a href="#" class="btn btn-default">Annuler</a>
                                <button type="submit" class="btn btn-info pull-right">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  