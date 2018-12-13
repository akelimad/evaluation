@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="filter-box mb40">
                        <h4 class="help-block">  <i class="fa fa-filter text-info"></i> Choisissez les critères de recherche que vous voulez <button class="btn btn-info btn-sm pull-right showFormBtn"> <i class="fa fa-chevron-down"></i></button></h4>
                        <form action="{{ url('users/filter') }}" class="criteresForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name"> Nom </label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ isset($name) ? $name :'' }}">
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
                        <h3 class="box-title"><i class="glyphicon glyphicon-user"></i> Liste des sociétés <span class="badge">{{ $results->total() }}</span></h3>
                        <div class="box-tools mb40">
                            <a onclick="return Crm.create()" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter</a>
                        </div>
                    </div>
                    @if(count($results)>0)
                        <div class="box-body table-responsive no-padding mb20">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th>Nom de la société</th>
                                    <th>Prénom du contact</th>
                                    <th>Nom du contact</th>
                                    <th>Email</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                @foreach($results as $key => $user)
                                <tr>
                                    <td> {{ $user->name }} </td>
                                    <td> {{ $user->first_name }} </td>
                                    <td> {{ $user->last_name }} </td>
                                    <td> {{ $user->email }} </td>
                                    <td class="text-center"> 
                                        {{ csrf_field() }} 
                                        <a href="javascript:void(0)" onclick="return Crm.edit({id: {{$user->id}}})" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer" > <i class="glyphicon glyphicon-pencil"></i> 
                                        </a>
                                        <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer le compte ?', 'Etes-vous sur de vouloir supprimer ce compte ?','Crm.delete', {id: {{$user->id}}}, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer"> <i class="fa fa-trash"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
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
        @if(isset($name))
            $(".showFormBtn i").toggleClass("fa-chevron-down fa-chevron-up")
            $(".criteresForm").fadeToggle()
        @endif
    })
</script>
@endsection