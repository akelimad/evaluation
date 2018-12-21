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
                @if(Session::has('import_success'))
                    @include('partials.alerts.success', ['messages' => Session::get('import_success') ])
                @endif
                @if(Session::has('exist_already'))
                    @include('partials.alerts.warning', ['messages' => Session::get('exist_already') ])
                @endif
                <div class="box box-primary">
                    <div class="filter-box mb40">
                        <h4 class="help-block showFormBtn">  <i class="fa fa-filter text-info"></i> Choisissez les critères de recherche que vous voulez <button class="btn btn-info btn-sm pull-right"> <i class="fa fa-chevron-down"></i></button></h4>
                        <form action="{{ url('users') }}" class="criteresForm" style="{{ isset($params) ? 'display: block;':'' }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name"> Nom </label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ isset($name) ? $name :'' }}">
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    <div class="form-group">
                                        <label for="department"> Département</label>
                                        <select name="department" id="dep" class="form-control">
                                            <option value=""></option>
                                            @foreach($departments as $dep)
                                                <option value="{{ $dep->id }}" {{ (isset($department) && $department == $dep->id) ? 'selected':'' }}>{{ $dep->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    <div class="form-group">
                                        <label for="function"> Fonction </label>
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
                                        <label for="role"> Rôle </label>
                                        <select name="role" id="role" class="form-control">
                                            <option value=""> === Choisissez === </option>
                                            @foreach($roles as $r)
                                            <option value="{{$r->id}}" {{ isset($role) && $role == $r->id ? 'selected' :'' }} > {{$r->name}} </option>
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
                        <h3 class="box-title"><i class="glyphicon glyphicon-user"></i> Liste des utilisateurs <span class="badge">{{$results->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a onclick="return chmUser.form({})" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter </a>
                            <a href="{{ url('users/import') }}" class="btn btn-success"><i class="fa fa-upload"></i> Importer</a>
                        </div>
                    </div>
                    @if(count($results)>0)
                        <div class="box-body table-responsive no-padding mb20">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>Nom complet</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Département</th>
                                    <th>Mentor</th>
                                    <th>Créé le</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                @foreach($results as $key => $user)
                                <tr>
                                    <td>
                                        <div class="wrap-checkItem">
                                            <input type="checkbox" class="usersId checkItem" autocomplete="off" value="{{$user->id}}" >
                                        </div>
                                    </td>
                                    <td> <a href="{{url('user/'.$user->id)}}">{{ $user->name." ".$user->last_name }}</a> </td>
                                    <td> {{ $user->email }} </td>
                                    <td> 
                                        @if(count($user->roles)>0) 
                                            @foreach($user->roles as $role)
                                                {{$role->name}}
                                            @endforeach
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>
                                        {{ is_numeric($user->service) ? App\Department::find($user->service)->title : '---' }}
                                    </td>
                                    <td> 
                                        @if($user->parent)
                                        <a href="{{url('user/'.$user->parent->id)}}">{{ $user->parent->name." ".$user->parent->last_name }}</a> 
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td> {{ Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}} </td>
                                    <td class="text-center"> 
                                        {{ csrf_field() }} 
                                        <a href="{{ url('user/'.$user->id) }}" class="btn-primary icon-fill" data-toggle="tooltip" title="Voir le profil"> <i class="fa fa-eye"></i> 
                                        </a>
                                        <a href="javascript:void(0)" onclick="return chmUser.form({{{$user->id}}})" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer" > <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        @if($user->email == env('SYS_ADMIN_EMAIL'))
                                        <a href="javascript:void(0)" class="btn-danger icon-fill disabled" data-toggle="tooltip" title="System admin ne peut pas être supprimé"> <i class="fa fa-trash" ></i> </a>
                                        @else
                                            <a href="javascript:void(0)" class="btn-danger icon-fill delete-user" data-id="{{ $user->id }}" data-toggle="tooltip" title="Supprimer"> <i class="fa fa-trash"></i> </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="sendInvitationBtn mb40">
                            <a onclick="return chmEntretien.entretiens()" class="btn btn-success"> <i class="fa fa-send"></i> Envoyer une invitation</a>
                        </div>

                        @include('partials.pagination')

                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
<script>
    $(function() {
        
    })
</script>
@endsection