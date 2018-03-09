@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                 @if (Session::has('attach_users_entretien'))
                    <div class="chm-alerts alert alert-success alert-white rounded">
                        <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
                        <div class="icon"><i class="fa fa-info-circle"></i></div>
                        <span> {!! Session::get('attach_users_entretien') !!} </span>
                    </div>
                @endif
                <div class="box box-primary">
                    <div class="filter-box mb40">
                        <h4>  <i class="fa fa-filter text-info"></i> Choisissez les critères de recherche que vous voulez </h4>
                        <form action="{{ url('users/filter') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name"> Nom </label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ isset($name) ? $name :'' }}">
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    <div class="form-group">
                                        <label for="service"> Service </label>
                                        <input type="text" name="service" id="service" class="form-control" value="{{ isset($name) ? $service :'' }}">
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    <div class="form-group">
                                        <label for="function"> Fonction </label>
                                        <input type="text" name="function" id="function" class="form-control" value="{{ isset($name) ? $function :'' }}">
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    <div class="form-group">
                                        <label for="role"> Rôle </label>
                                        <select name="role" id="role" class="form-control">
                                            <option value=""> === Choisissez === </option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{ isset($roleSelected) && $roleSelected == $role->id ? 'selected' :'' }} > {{$role->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Rechercher</button>
                                    <a href="{{url('users')}}" class="btn btn-default"><i class="fa fa-refresh"></i> Actualiser</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title">La liste des utilisateurs <span class="badge">{{$users->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="{{ url('user/create') }}" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($users)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered table-inversed-blue">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>Nom complet</th>
                                    <th>Email</th>
                                    <th>Société</th>
                                    <th>Rôle</th>
                                    <th>Service</th>
                                    <th>Fonction</th>
                                    <th>Mentor</th>
                                    <th>Date d'embauche</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                                @foreach($users as $key => $user)
                                <tr>
                                    <td>
                                        <div class="wrap-checkItem">
                                            <input type="checkbox" class="usersId checkItem" autocomplete="off" value="{{$user->id}}" >
                                        </div>
                                    </td>
                                    <td> <a href="{{url('user/'.$user->id)}}">{{ $user->name." ".$user->last_name }}</a> </td>
                                    <td> {{ $user->email }} </td>
                                    <td> {{ $user->society ? $user->society : '---' }} </td>
                                    <td> 
                                        @if(count($user->roles)>0) 
                                            @foreach($user->roles as $role)
                                                {{$role->name}}
                                            @endforeach
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>{{ $user->service ? $user->service : '---' }}</td>
                                    <td>{{ $user->function ? $user->function : '---' }}</td>
                                    <td> {{ $user->user_id != 0 ? $user->parent->email : '---' }} </td>
                                    <td> {{ Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}} </td>
                                    <td> 
                                        @if($user->status == 0) <span class="label label-danger">Désactivé</span> @else <span class="label label-success">Activé</span> </td>
                                        @endif
                                    <td>  
                                        <a href="{{ url('user/'.$user->id) }}" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $users->links() }}
                        </div>
                        <div class="sendInvitationBtn mb40">
                            <a onclick="return chmEntretien.entretiens()" class="btn btn-success"> <i class="fa fa-send"></i> Envoyer une invitation</a>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  