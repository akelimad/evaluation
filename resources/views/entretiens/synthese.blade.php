@extends('layouts.app')
@section('title', 'Synthèse')
@section('content')
  <section class="content evaluations">
    <div class="row">
      <div class="col-md-12">
        <div class="card box box-primary">
          <h3 class="mb40"> Détails de l'entretien annuel d'évaluation: {{$e->titre}} - {{ $user->fullname() }} </h3>
          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              <div class="active tab-pane">
                <div class="row">
                  <label class="control-label col-md-4">Date limite pour l'auto-évaluation :</label>
                  <div class="col-md-8"> {{ Carbon\Carbon::parse($e->date)->format('d/m/Y')}} </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Date limite pour l'évaluation manager :</label>
                  <div class="col-md-8"> {{ Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}} </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Validation du collaborateur :</label>
                  <div class="col-md-8"><span class="label label-{{App\Entretien::answered($e->id, $user->id) ? 'success':'danger'}} empty"> </span>
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Validation du mentor :</label>
                  <div class="col-md-8"><span class="label label-{{App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id) ? 'success':'danger'}} empty"> </span>
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Manager :</label>
                  <div class="col-md-8"> {{$user->parent ? $user->parent->name." ".$user->parent->last_name : $user->name." ".$user->last_name}} </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Titre :</label>
                  <div class="col-md-8"> {{ $e->titre }} </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Collaborateur(trice) évalué(e) :</label>
                  <div class="col-md-8"> {{ $user->name." ".$user->last_name }} </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Adresse email :</label>
                  <div class="col-md-8">
                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    @role(['MENTOR', 'ADMIN'])
                    @if(!App\Entretien::answered($e->id, $user->id))
                      <a href="{{url('notifyUserInterview', ['entretien'=>$e, 'user'=> $user])}}"
                         class="btn btn-primary"> <i class="fa fa-envelope"></i> Relancer</a>
                    @endif
                    @endrole
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Fonction :</label>
                  <div class="col-md-8"> {{ $user->function ? App\Fonction::findOrFail($user->function)->title :'---' }} </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <label class="control-label col-md-4">Département :</label>
                  <div class="col-md-8"> {{ $user->service ? App\Department::findOrFail($user->service)->title : '---' }} </div>
                  <div class="clearfix"></div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <a href="{{ route('home') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour</a>
                    <a href="{{ route('anglets.evaluation-annuelle', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-primary pull-right">Suivant <i class="fa fa-long-arrow-right"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
  