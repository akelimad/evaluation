@extends('layouts.app')
@section('content')
    <section class="content profile">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#activity" data-toggle="tab">Informations personnelles</a></li>
                            <li><a href="#timeline" data-toggle="tab">Informations professionnelles</a></li>
                            <li><a href="#settings" data-toggle="tab">Mes préférences</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Société</label>
                                    <div class="col-md-9">{{$user->society}}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Photo</label>
                                    <div class="col-md-4">
                                        @if(!empty($user->avatar))
                                            <img src="{{asset('avatar/'.$user->avatar)}}" alt="" class="profile-user-img img-responsive img-circle">
                                        @else
                                            <img src="{{asset('img/avatar.png')}}" alt="" class="profile-user-img img-responsive img-circle">
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
                                    <label class="control-label col-md-3">Code postale</label>
                                    <div class="col-md-9">{{ $user->zip_code }}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Ville</label>
                                    <div class="col-md-9">{{ $user->city }}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Téléphone mobile</label>
                                    <div class="col-md-9">{{ $user->tel }}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Téléphone fixe</label>
                                    <div class="col-md-9">{{ $user->fix }}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">A propos de moi</label>
                                    <div class="col-md-9">{{ $user->about }}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Notification par email</label>
                                    <div class="col-md-9"><span class="label label-success"> Oui</span></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="timeline">
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item"><b>Date d'entrée</b> <span> {{$user->created_at}} </span></li>
                                    <li class="list-group-item"><b>Fonction </b> <span> {{$user->function}} </span></li>
                                    <li class="list-group-item"><b>Service</b> <span> {{$user->service}} </span></li>
                                </ul>
                            </div>

                            <div class="tab-pane" id="settings">
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>Afficher l'aide contextuelle</b> 1,322
                                    </li>
                                    <li class="list-group-item">
                                        <b>Recevoir les notifications par email</b> 1,322
                                    </li>
                                    <li class="list-group-item">
                                        <b>Nombre de ligne par page</b> 1,322
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  