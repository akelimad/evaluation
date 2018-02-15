@extends('layouts.app')
@section('content')
    <section class="content index">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des utilisateurs</h3>
                        <div class="box-tools">
                            <a href="{{ url('user/create') }}" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
                      </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Société</th>
                                <th>Rôle</th>
                                <th>Mentor</th>
                                <th>Date d'embauche</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                            @foreach($users as $key => $user)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> {{ $user->name }} {{ $user->last_name }} </td>
                                <td> {{ $user->email }} </td>
                                <td> {{ $user->society }} </td>
                                <td> 
                                    @if(count($user->roles)>0) 
                                        @foreach($user->roles as $role)
                                            {{$role->name}}
                                        @endforeach
                                    @else
                                        ---
                                    @endif
                                </td>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  