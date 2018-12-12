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
                        <h3 class="box-title"><i class="glyphicon glyphicon-user"></i> Liste des sociétés <span class="badge">0</span></h3>
                        <div class="box-tools mb40">
                            <a onclick="return chmUser.create()" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($results)>0)
                        <div class="box-body table-responsive no-padding mb20">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th>Nom complet</th>
                                    <th>Email</th>
                                    <th>Société</th>
                                    <th>Créé le</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                @foreach($results as $key => $user)
                                <tr>
                                    <td> <a href="{{url('user/'.$user->id)}}">{{ $user->name." ".$user->last_name }}</a> </td>
                                    <td> {{ $user->email }} </td>
                                    <td> {{ $user->society ? $user->society : '---' }} </td>
                                    <td>
                                        {{ App\Setting::asList('society.services', false, true)[$user->service] }}
                                    </td>
                                    <td> {{ Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}} </td>
                                    <td class="text-center"> 
                                        {{ csrf_field() }} 
                                        <a href="{{ url('user/'.$user->id) }}" class="btn-primary icon-fill" data-toggle="tooltip" title="Voir le profil"> <i class="fa fa-eye"></i> 
                                        </a>
                                        <a href="javascript:void(0)" onclick="return chmUser.edit({id: {{$user->id}}})" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer" > <i class="glyphicon glyphicon-pencil"></i> 
                                        </a>
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
        @if(isset($name))
            $(".showFormBtn i").toggleClass("fa-chevron-down fa-chevron-up")
            $(".criteresForm").fadeToggle()
        @endif
    })
</script>
@endsection