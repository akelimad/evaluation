@extends('layouts.app')
@section('content')
    <section class="content profile">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#personels" data-toggle="tab">Informations personnelles</a></li>
                            @if(!$user->hasRole('ADMIN'))
                            <li><a href="#profesionnels" data-toggle="tab">Informations professionnelles</a></li>
                            @endif
                        </ul>
                        <div class="tab-content mb20">
                            <div class="active tab-pane" id="personels">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        @if(Auth::user()->hasRole('ADMIN'))
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
                                @if(!Auth::user()->hasRole('ADMIN'))
                                <div class="form-group">
                                    <label class="control-label col-md-3">Téléphone mobile</label>
                                    <div class="col-md-9">{{ $user->tel ? $user->tel : '---' }}</div>
                                    <div class="clearfix"></div>
                                </div>
                                @endif
                            </div>
                            @if(!$user->hasRole('ADMIN'))
                            <div class="tab-pane" id="profesionnels">
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
                                        {{ is_numeric($user->function) ? App\Fonction::find($user->function)->title : '---' }}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Département</label>
                                    <div class="col-md-9">
                                        {{ is_numeric($user->service) ? App\Department::find($user->service)->title : '---' }}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-info"><i class="fa fa-long-arrow-left"></i> Retour</a>
                        @if(Auth::user()->id == $user->id)
                            @if(Auth::user()->hasRole('ADMIN'))
                                <a href="javascript:void(0)" onclick="return Crm.edit({id: {{$user->id}}})" class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"></i> Mettre à jour </a>
                            @else
                                <a href="javascript:void(0)" onclick="return chmUser.form({{{ $user->id }}})" class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"></i> Mettre à jour </a>
                            @endif
                            
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
