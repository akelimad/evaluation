@extends('layouts.app')
@section('title', 'Profil')
@section('breadcrumb')
  <li><a href="{{ route('users') }}" class="text-blue">Utilisateurs</a></li>
  <li>Profil de {{ $user->fullname() }}</li>
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
                      <img src="{{ App\User::logo($user->id) }}" width="130" height="130" alt="" class="user-profile-img img-circle">
                    @else
                      <img src="{{ App\User::avatar($user->id) }}" width="130" alt="" class="user-profile-img img-circle">
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
                    {{ App\Fonction::find($user->function) ? App\Fonction::find($user->function)->title : '---' }}
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Département</label>
                  <div class="col-md-9">
                    {{ App\Department::find($user->service) ? App\Department::find($user->service)->title : '---' }}
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
                  <label class="control-label col-md-3">Nom de l'équipe</label>
                  <div class="col-md-9">
                    @forelse($user->teams as $team)
                      <span class="badge">{{ $team->name }}</span>
                    @empty
                      ---
                    @endforelse
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="tab-pane" id="entretiens">
                <div class="p-30">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <tbody>
                      @forelse($user->entretiens as $entretien)
                        <tr>
                          <td>
                            <span><i class="fa fa-search fa-1x fa-fw"></i> <a href="javascript:void(0)" onclick="return chmEntretien.apercu({eid: {{$entretien->id}}, uid: {{$user->id}} })">{{ $entretien->titre }} </a></span>
                            <span class="label label-{{ $entretien->isActif() ? 'success':'danger' }} pl-10 pr-10 ml-30 font-14">{{ $entretien->getStatus() }}</span></td>
                          <td>{{ date('d/m/Y', strtotime($entretien->date_limit)) }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="2">
                            <div class="alert alert-info font-14">Aucun résultat trouvé</div>
                          </td>
                        </tr>
                      @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="feedback">
                <div class="p-30">
                  En construction
                </div>
              </div>
              <div class="tab-pane" id="preferences">
                @php($settings = json_decode(Auth::user()->settings))
                <div class="form-group">
                  <form action="{{ url('config/settings/store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="col-md-4">
                      <div class="form-check">
                        <input type="checkbox" name="settings[toggle_sidebar]" id="toggle-sidebar" value="1" {{isset($settings->toggle_sidebar) && $settings->toggle_sidebar == 1 ? 'checked' : ''}}>
                        <label for="toggle-sidebar">Toggle side bar</label>
                      </div>
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
                <a
                    href="javascript:void(0)"
                    chm-modal="{{ route('company.form', ['id' => $user->id]) }}"
                    chm-modal-options='{"form":{"attributes":{"id":"companyForm"}}}'
                    class="btn btn-primary"
                ><i class="fa fa-pencil"></i>&nbsp;{{ "Mettre à jour" }}</a>
              @else
                <a
                    href="javascript:void(0)"
                    chm-modal="{{ route('user.form', ['id' => $user->id]) }}"
                    chm-modal-options='{"form":{"attributes":{"id":"userForm"}}}'
                    class="btn btn-primary"
                ><i class="fa fa-pencil"></i>&nbsp;{{ "Mettre à jour" }}</a>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
