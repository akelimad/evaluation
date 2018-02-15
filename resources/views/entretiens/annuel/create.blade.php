@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ajouter un entretien d'evaluation</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" action="{{ url('entretiens/store') }}">
                            <input type="hidden" name="type" value="annuel">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="date" class="control-label">Date de l'entretien</label>
                                    <input type="text" name="date" class="form-control" id="datepicker" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_limit" class="control-label">Date limite</label>
                                    <input type="text" name="date_limit" class="form-control" id="datepicker" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="titre" class="control-label">Titre</label>
                                    <input type="text" name="titre" class="form-control" id="titre" placeholder="">
                                </div>
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
  