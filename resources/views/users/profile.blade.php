@extends('layouts.app')
@section('content')
    <section class="content profile">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#personels" data-toggle="tab">Informations personnelles</a></li>
                            <li><a href="#profesionnels" data-toggle="tab">Informations professionnelles</a></li>
                        </ul>
                        <div class="tab-content mb20">
                            <div class="active tab-pane" id="personels">
                                <div class="form-group">
                                    <div class="col-md-4">
                                    <!-- <label class="control-label">Photo</label> -->
                                        @if(!empty($user->avatar))
                                            <img src="{{asset('avatars/'.$user->avatar)}}" alt="" class="user-profile-img img-responsive img-circle">
                                        @else
                                            <img src="{{asset('img/avatar.png')}}" alt="" class="user-profile-img img-responsive img-circle">
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
                                    <label class="control-label col-md-3">Ville</label>
                                    <div class="col-md-9">{{ $user->city ? $user->city : '---' }}</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Téléphone mobile</label>
                                    <div class="col-md-9">{{ $user->tel ? $user->tel : '---' }}</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="profesionnels">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Date d'entrée</label>
                                    <div class="col-md-9">
                                        {{ Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fonction</label>
                                    <div class="col-md-9">
                                        {{ $user->function ? App\Fonction::find($user->function)->title : '---' }}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Département</label>
                                    <div class="col-md-9">
                                        {{ $user->service ? App\Department::find($user->service)->title : '---' }}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-info"><i class="fa fa-long-arrow-left"></i> Retour</a>
                        @if(Auth::user()->id == $user->id)
                            <a href="javascript:void(0)" onclick="return chmUser.form({{{ $user->id }}})" class="btn btn-primary"> <i class="glyphicon glyphicon-pencil"></i> Mettre à jour </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
