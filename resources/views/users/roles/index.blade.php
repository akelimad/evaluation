@extends('layouts.app')
@section('title', 'Rôles')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Liste des rôles</h3>
                        <div class="box-tools">
                            <a class="btn bg-maroon" onclick="return chmRole.create()" data-toggle="tooltip" title="Ajouter un rôle"> <i class="fa fa-plus"></i> Ajouter </a>
                      </div>
                    </div>
                    @if(count($roles)>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped table-inversed-blue">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Le nom d'affichage</th>
                                <th>Description</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            @foreach($roles as $key => $role)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> {{ $role->name }} </td>
                                <td> {{ $role->display_name }} </td>
                                <td> {{ $role->description }} </td>
                                <td class="text-center">  
                                    <a href="javascript:void(0)" onclick="return chmRole.edit({id:{{$role->id}}})" class="btn-warning icon-fill" data-toggle="tooltip" title="Modifier"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  