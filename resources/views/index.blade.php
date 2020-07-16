@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
    <section class="content index">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile box-widget widget-user">
                        <h3 class="widget-user-username">Bienvenue {{Auth::user()->displayName()}}</h3>
                        @if(!Auth::user()->hasRole('ADMIN'))
                        <p>Voici les informations de votre Manager :</p>
                        @endif
                        @if(Auth::user()->hasRole('ADMIN'))
                        <div class="home-box-img-profile">
                            <img src="{{ App\User::logo($user->id) }}" alt="" class="text-center img-responsive">
                        </div>
                        @else
                        <img src="{{ App\User::avatar($mentor->id) }}" alt="" class="profile-user-img img-responsive img-circle">
                        @endif
                        @if(!Auth::user()->hasRole('ADMIN'))
                        <h3 class="profile-username text-center">{{ $mentor->name }} {{ $mentor->last_name }} </h3>
                        <p class="text-muted text-center">
                            {{ (!empty($mentor->function)) ? App\Fonction::findOrFail($mentor->function)->title : '---' }}
                        </p>
                        @endif

                        <ul class="list-group list-group-unbordered">
                            @if(!Auth::user()->hasRole('ADMIN'))
                            <li class="list-group-item"><b>Département : </b>
                                <a class="">{{ (!empty($mentor->service)) ? App\Department::findOrFail($mentor->service)->title : '---' }}</a>
                            </li>
                            <li class="list-group-item"><b>Téléphone mobile: </b> <a class="">{{ $mentor->tel ? $mentor->tel : '---' }}</a></li>
                            @endif
                            <li class="list-group-item"><b>Email: </b> <a class="">{{ $mentor->email }}</a></li>
                        </ul>
                        @role(["COLLABORATEUR"])
                        <p> <i>N'hésitez pas à solliciter votre Manager si vous avez la moindre question concernant votre suivi RH.</i> </p>
                        @endrole
                    </div>
                </div>
                <!-- todo -->
                @if ( 1 == 2 )
                <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title"> Mes actualités </h3>
                    </div>
                    <div class="box-body">
                        <ul class="list-unstyled news">
                            <li> <i class="fa fa-bell-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> <span class="help-block pull-right">13/02/2018</span></li>
                            <li> <i class="fa fa-bell-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> <span class="help-block pull-right">13/02/2018</span></li>
                            <li> <i class="fa fa-bell-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> <span class="help-block pull-right">13/02/2018</span></li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-9">
                <div class="card portlet box box-primary">
                    <div class="nav-tabs-custom portlet-title">
                        <div class="caption caption-red">Mes entretiens</div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#entretiens" data-toggle="tab"> Entretiens </a></li>
                            <!-- <li><a href="#objectifs" data-toggle="tab"> Objectifs  </a></li> -->
                            {{--<li><a href="#formations" data-toggle="tab"> Formations </a></li>--}}
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="entretiens">
                                @if(App\User::getMentor(Auth::user()->id) && count($entretiens)>0)
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Titre </th>
                                                <th>Date d'expiration</th>
                                                <th class="text-center">Collaborateur</th>
                                                <th class="text-center">Manager</th>
                                                <th class="text-center">RH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entretiens as $e)
                                            <tr>
                                                <td>
                                                    <a href="{{ url('entretiens/'.$e->id.'/u/'.Auth::user()->id) }}">{{$e->titre}}</a>
                                                </td>
                                                <td>
                                                    {{ Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}
                                                </td>
                                                <td class="text-center">
                                                    <span class="label label-{{App\Entretien::answered($e->id, Auth::user()->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answered($e->id, Auth::user()->id) ? 'Remplie le '.Carbon\Carbon::parse(App\Entretien::answered($e->id, Auth::user()->id)->user_updated_at)->format('d/m/Y à H:i') : 'Vous avez une évaluation à remplir'}}"> </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="label label-{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'Validée par mentor le '.Carbon\Carbon::parse(App\Entretien::answered($e->id, Auth::user()->id)->mentor_updated_at)->format('d/m/Y à H:i') :'Pas encore validée par votre mentor'}}"> </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="label label-danger empty"> </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    @include('partials.alerts.info', ['messages' => "Aucun entretien trouvé ... !!" ])
                                @endif
                            </div>
                            <!-- <div class="tab-pane" id="objectifs">
                                <div class="box-body table-responsive no-padding">
                                    objectifs
                                </div>
                            </div> -->
                            <!-- {{--<div class="tab-pane" id="formations">--}}
                                {{--<div class="box-body table-responsive no-padding">--}}
                                    {{--@if( count($formations)>0 )--}}
                                    {{--<table class="table table-hover table-striped">--}}
                                        {{--<thead>--}}
                                            {{--<tr>--}}
                                                {{--<th>Entretien </th>--}}
                                                {{--<th>Date</th>--}}
                                                {{--<th>Exercice</th>--}}
                                                {{--<th>Formation</th>--}}
                                                {{--<th>Statut</th>--}}
                                            {{--</tr>--}}
                                        {{--</thead>--}}
                                        {{--<tbody>--}}
                                            {{--@foreach($formations as $formation)--}}
                                            {{--<tr>--}}
                                                {{--<td>{{ $formation->entretien->titre }}</td>--}}
                                                {{--<td>{{ $formation->date }}</td>--}}
                                                {{--<td>{{ $formation->exercice }}</td>--}}
                                                {{--<td>{{ $formation->title }}</td>--}}
                                                {{--<td>{{ $formation->title }}</td>--}}
                                                {{--<td>--}}
                                                    {{--@if($formation->status == 0) En attente --}}
                                                    {{--@elseif($formation->status == 1) Refusé --}}
                                                    {{--@elseif($formation->status == 2) Accepté --}}
                                                    {{--@endif--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}
                                            {{--@endforeach--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                    {{--@else--}}
                                        {{--@include('partials.alerts.info', ['messages' => "Aucune formation trouvée ... !!" ])--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}} -->
                        </div>
                    </div>
                </div>
                @if(count($collaborateurs)>0)
                <div class="card portlet box box-primary">
                    <div class="nav-tabs-custom portlet-title">
                        <div class="caption caption-red">Mes collaborateurs</div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#aa" data-toggle="tab"> Entretiens </a></li>
                            <!-- <li><a href="#bb" data-toggle="tab"> Objectifs  </a></li> -->
                            {{--<li><a href="#cc" data-toggle="tab"> Formations </a></li>                            --}}
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="aa">
                                @if(count($collaborateurs)>0)
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom et prénom</th>
                                                <th>Fonction</th>
                                                <th>Type d'évaluation</th>
                                                <th class="text-center">Collaborateur</th>
                                                <th class="text-center">Manager</th>
                                                <th class="text-center">RH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($collaborateurs as $coll)
                                            @foreach($coll->entretiens as $en)
                                            <tr>
                                                <td>
                                                    <a href="{{url('user/'.$coll->id)}}">{{$coll->name." ".$coll->last_name}}</a>
                                                </td>
                                                <td>
                                                    {{ $coll->function ? App\Fonction::findOrFail($coll->function)->title : '---' }}
                                                </td>
                                                <td>
                                                    <a href="{{url('entretiens/'.$en->id.'/u/'.$coll->id)}}">{{ $en->titre }}</a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="label label-{{App\Entretien::answered($en->id, $coll->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answered($en->id, $coll->id) ? 'Remplie le '.Carbon\Carbon::parse(App\Entretien::answered($en->id, $coll->id)->user_updated_at)->format('d/m/Y H:i') :'Pas encore rempli par '.$coll->name }}"> </span>

                                                </td>
                                                <td class="text-center">
                                                    <span class="label label-{{App\Entretien::answeredMentor($en->id, $coll->id, Auth::user()->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answeredMentor($en->id, $coll->id, Auth::user()->id) ? 'Validée par mentor le '.Carbon\Carbon::parse(App\Entretien::answeredMentor($en->id, $coll->id, Auth::user()->id)->mentor_updated_at)->format('d/m/Y H:i') :'Veuillez valider l\'évaluation de '.$coll->name}}"> </span>

                                                </td>
                                                <td class="text-center">
                                                    <span class="label label-danger empty"> </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                                @endif
                            </div>
                            <!-- <div class="tab-pane" id="bb">
                                <div class="box-body table-responsive no-padding">
                                    objetifs
                                </div>
                            </div> -->
                            {{--<div class="tab-pane" id="cc">--}}
                                {{--<div class="box-body table-responsive no-padding">--}}
                                    {{--<div class="box-body table-responsive no-padding">--}}
                                    {{--<table class="table table-hover table-striped">--}}
                                        {{--<thead>--}}
                                            {{--<tr>--}}
                                                {{--<th>Collaborateur </th>--}}
                                                {{--<th>Entretien </th>--}}
                                                {{--<th>Date</th>--}}
                                                {{--<th>Exercice</th>--}}
                                                {{--<th>Formation</th>--}}
                                            {{--</tr>--}}
                                        {{--</thead>--}}
                                        {{--<tbody>--}}
                                            {{--@foreach($collaborateurs as $coll)--}}
                                                {{--@foreach($coll->entretiens as $en)--}}
                                                    {{--@foreach($coll->formations as $formation)--}}
                                                    {{--<tr>--}}
                                                        {{--<td>--}}
                                                            {{--<a href="{{url('user/'.$coll->id)}}">{{$coll->name." ".$coll->last_name}}</a>--}}
                                                        {{--</td>--}}
                                                        {{--<td>{{ $en->titre }}</td>--}}
                                                        {{--<td>{{ Carbon\Carbon::parse($formation->date)->format('d/m/Y')}}</td>--}}
                                                        {{--<td>{{ $formation->exercice }}</td>--}}
                                                        {{--<td>{{ $formation->title }}</td>--}}
                                                    {{--</tr>--}}
                                                    {{--@endforeach--}}
                                                {{--@endforeach--}}
                                            {{--@endforeach--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection

@section('javascript')
<script>
    // this to show popup message for ADMIN only after authentication
    $(window).on('load', function() {
        @if(\Auth::user()->hasRole('ADMIN') && session('popup'))
            setTimeout (function () { 
                swal({
                    title: "Bienvenue", 
                    text: "Bienvenue {{Auth::user()->name}} à votre espace d'administration", 
                    type: "success"
                }, {
                    @php(session()->forget('popup'))
                });
            }, 2000)
        @endif
    });
</script>
@endsection