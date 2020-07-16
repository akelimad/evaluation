@extends('layouts.app')
@section('title', 'Profil')
@section('breadcrumb')
  <li>Profil</li>
@endsection
@section('content')
  <section class="content profile">
    <div class="row">
      <div class="col-md-12">
        <div class="card p-30">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#generals" data-toggle="tab">Informations collaborateur</a></li>
              <li><a href="#entretiens" data-toggle="tab">Entretiens</a></li>
              <li><a href="#feedback" data-toggle="tab">Feedback</a></li>
              <li><a href="#preferences" data-toggle="tab">Préférences</a></li>
            </ul>
            <div class="tab-content mb20">
              <div class="active tab-pane" id="generals">
                <div class="form-group">
                  <div class="col-md-4">
                    @if($user->hasRole('ADMIN'))
                      <img src="{{ App\User::logo($user->id) }}" alt="" class="img-responsive">
                    @else
                      <img src="{{ App\User::avatar($user->id) }}" alt="" class="user-profile-img img-responsive img-circle">
                    @endif
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Prénom</label>
                  <div class="col-md-9">{{ $user->name }}</div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Nom</label>
                  <div class="col-md-9">{{ $user->last_name }}</div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Adresse email</label>

                  <div class="col-md-9">{{ $user->email }}</div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Téléphone mobile</label>
                  <div class="col-md-9">{{ $user->tel ? $user->tel : '---' }}</div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Création du compte</label>
                  <div class="col-md-9">
                    {{ Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Fonction</label>
                  <div class="col-md-9">
                    {{ is_numeric($user->function) ? App\Fonction::findOrFail($user->function)->title : '---' }}
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Département</label>
                  <div class="col-md-9">
                    {{ is_numeric($user->service) ? App\Department::findOrFail($user->service)->title : '---' }}
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Date de recrutement</label>
                  <div class="col-md-9">
                    {{ !empty($user->date_recruiting) ? $user->date_recruiting : '---' }}
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Matricule</label>
                  <div class="col-md-9">
                    {{ !empty($user->mle) ? $user->mle : '---' }}
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Manager</label>
                  <div class="col-md-9">
                    @if ($user->parent)
                    <a href="{{ route('user.profile', ['id' => $user->parent->id]) }}">{{ $user->parent->fullname() }}</a>
                    @else
                      ---
                    @endif
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Equipe</label>
                  <div class="col-md-9">
                    ---
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="tab-pane" id="entretiens">
                <div class="p-30">
                  entretiens content
                </div>
              </div>
              <div class="tab-pane" id="feedback">
                <div class="p-30">
                  feedback content
                </div>
              </div>
              <div class="tab-pane" id="preferences">
                @php($settings = json_decode(Auth::user()->settings))
                <div class="form-group">
                  <form action="{{ url('config/settings/store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="col-md-4">
                      <input type="checkbox" name="settings[toggle_sidebar]" id="toggle-sidebar"
                             value="1" {{isset($settings->toggle_sidebar) && $settings->toggle_sidebar == 1 ? 'checked' : ''}}>
                      <label for="toggle-sidebar">Toggle side bar</label>

                      <p class="help-block">Permet de réduire la taille du side bar.</p>
                    </div>
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Enregistrer</button>
                    </div>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="actions">
            <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour</a>
            @if(Auth::user()->id == $user->id)
              @if(Auth::user()->hasRole('ADMIN'))
                <a href="javascript:void(0)" onclick="return Crm.form({{{$user->id}}})" class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"></i> Mettre à jour</a>
              @else
                <a href="javascript:void(0)" onclick="return chmUser.form({{{ $user->id }}})" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i> Mettre à jour</a>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
