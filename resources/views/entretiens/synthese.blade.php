@extends('layouts.app')
@section('title', 'Synthèse')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                @if(session()->has('message'))
                    @include('partials.alerts.success', ['messages' => session()->get('message') ])
                @endif
                <div class="card box box-primary">
                    <h3 class="mb40"> Détails de l'entretien annuel d'évaluation: {{$e->titre}} - {{ $user->name." ".$user->last_name }} </h3>
                    <div class="nav-tabs-custom">
                        @include('partials.tabs')
                        <div class="tab-content">
                            <div class="active tab-pane">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Date de l'entretien : </label>
                                    <div class="col-md-9"> {{ Carbon\Carbon::parse($e->date)->format('d/m/Y')}} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Entretien à clôturer avant fin : </label>
                                    <div class="col-md-9"> {{ Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Validation du collaborateur :</label>
                                    <div class="col-md-9"> <span class="label label-{{App\Entretien::answered($e->id, $user->id) ? 'success':'danger'}} empty"> </span> </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Validation du mentor :</label>
                                    <div class="col-md-9"> <span class="label label-{{App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id) ? 'success':'danger'}} empty"> </span> </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Manager :</label>
                                    <div class="col-md-9"> {{$user->parent ? $user->parent->name." ".$user->parent->last_name : $user->name." ".$user->last_name}} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Titre :</label>
                                    <div class="col-md-9"> {{ $e->titre }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Collaborateur(trice) évalué(e) :</label>
                                    <div class="col-md-9"> {{ $user->name." ".$user->last_name }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Adresse email :</label>
                                    <div class="col-md-9"> 
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> 
                                        @role(['MENTOR', 'ADMIN'])
                                        @if(!App\Entretien::answered($e->id, $user->id))
                                        <a href="{{url('notifyUserInterview', ['entretien'=>$e, 'user'=> $user])}}" class="btn btn-primary"> <i class="fa fa-envelope"></i> Relancer</a>
                                        @endif
                                        @endrole
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fonction :</label>
                                    <div class="col-md-9"> {{ $user->function ? App\Fonction::findOrFail($user->function)->title :'---' }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Département :</label>
                                    <div class="col-md-9"> {{ $user->service ? App\Department::findOrFail($user->service)->title : '---' }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
  