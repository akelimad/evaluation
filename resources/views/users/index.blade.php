@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('breadcrumb')
  <li>Utilisateurs</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="">
          <div class="box-header mb-0 pb-0">
            <h4 class="help-block showFormBtn m-0"><span class="fa fa-search"></span> Options de recherche <button class="btn btn-info btn-sm pull-right pt-0 pb-0"><i class="fa fa-chevron-down"></i></button>
            </h4>
          </div>
          <div class="box-body filter-box p-0" style="display: {{ str_contains(\Request::fullurl(), '?') ? 'block':'none' }}">
            <form action="{{ url('users') }}" class=" bg-white p-15">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="name">Mot clé</label>
                    <input type="text" name="q" id="q" class="form-control" value="{{ Request::get('q', '') }}">
                  </div>
                </div>
                <div class=" col-md-3">
                  <div class="form-group">
                    <label for="department">Département</label>
                    <select name="department" id="dep" class="form-control">
                      <option value=""></option>
                      @foreach($departments as $dep)
                        <option value="{{ $dep->id }}" {{ Request::get('department', '') ? 'selected':'' }}>{{ $dep->title }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class=" col-md-3">
                  <div class="form-group">
                    <label for="function">Fonction</label>
                    <select name="function" id="function" class="form-control">
                      <option value=""></option>
                      @foreach($fonctions as $func)
                        <option value="{{ $func->id }}" {{ (isset($function) && $function == $func->id) ? 'selected':'' }}>{{ $func->title }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class=" col-md-3">
                  <div class="form-group">
                    <label for="role">Rôle</label>
                    <select name="role" id="role" class="form-control">
                      <option value=""></option>
                      @foreach($roles as $r)
                        <option value="{{$r->id}}" {{ isset($role) && $role == $r->id ? 'selected' :'' }} > {{$r->name}} </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class=" col-md-3">
                  <div class="form-group">
                    <label for="role">Equipe</label>
                    <select name="team" id="role" class="form-control">
                      <option value=""></option>
                      @foreach($teams as $t)
                        <option value="{{$t->id}}" {{ app('request')->input('team') && app('request')->input('team') == $t->id ? 'selected':'' }}> {{$t->name}} </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Rechercher</button>
                  <a href="{{url('users')}}" class="btn btn-default"><i class="fa fa-refresh"></i> Réinitialiser</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="glyphicon glyphicon-user"></i> Liste des utilisateurs <span class="badge">{{$results->total()}}</span></h3>

            <div class="box-tools mb40">
              <a onclick="return chmUser.form({})" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter </a>
              <a href="{{ url('users/import') }}" class="btn btn-success"><i class="fa fa-upload"></i> Importer</a>
            </div>
          </div>
          @if(count($results)>0)
            <div class="box-body table-responsive no-padding mb20">
              <div chm-table="{{ route('users.table') }}"
                   chm-table-options='{"with_ajax": true}'
                   chm-table-params='{{ json_encode(request()->query->all()) }}'
                   id="UsersTableContainer"
              ></div>
            </div>

            <div class="sendInvitationBtn mb40">
              <a onclick="return chmEntretien.entretiens()" class="btn btn-success"> <i class="fa fa-send"></i> Envoyer une invitation</a>
            </div>

            @include('partials.pagination')

          @else
            @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
          @endif

        </div>
      </div>
    </div>
  </section>
@endsection
