@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                @if(Session::has('update_formation'))
                    @include('partials.alerts.success', ['messages' => Session::get('update_formation') ])
                @endif
                    <h3 class="mb40"> Liste des formations <span class="badge">{{ $formations->total() }}</span> : {{ $e->titre }} - {{ $user->name." ".$user->last_name }} </h3>
                    <div class="nav-tabs-custom">
                        @include('partials.tabs')
                        <div class="tab-content">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center"> Souhaits </div>
                                <div class="panel-body">
                                @if(count($formations)>0)
                                    <div class="box-body table-responsive no-padding mb40">
                                        <table class="table table-hover table-striped text-center">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Exercice </th>
                                                    <th>Formation demandée</th>
                                                    <th>Description</th>
                                                    <th>Etat</th>
                                                    <th>Réalisé</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($formations as $f)
                                                <form action="{{url('entretiens/formations/'.$f->id.'/mentorUpdate')}}" method="post">
                                                    <input type="hidden" name="_method" value="PUT">
                                                    {{ csrf_field() }}
                                                <tr>
                                                    <td>{{ Carbon\Carbon::parse($f->date)->format('d/m/Y')}}</td>
                                                    <td>{{$f->exercice}}</td>
                                                    <td>{{$f->title}}</td>
                                                    <td><a title="{{$f->coll_comment}}" data-toggle="tooltip"><i class="fa fa-comment"></i></a></td>
                                                    <td>
                                                        @if($user->id == Auth::user()->id)
                                                            <span class="label label-@if($f->status == 0)default @elseif($f->status == 1)danger @elseif($f->status == 2)success @endif"> @if($f->status == 0)En attente @elseif($f->status == 1)Refusé @elseif($f->status == 2)Accepté @endif </span>
                                                        @else
                                                        <select name="status" id="status" class="label-@if($f->status == 0)default @elseif($f->status == 1)danger @elseif($f->status == 2)success @endif" {{$user->id == Auth::user()->id ? 'disabled':'' }} >
                                                            <option value="0" {{$f->status == 0 ? 'selected':''}} >En attente</option>
                                                            <option value="1" {{$f->status == 1 ? 'selected':''}} >Refusé</option>
                                                            <option value="2" {{$f->status == 2 ? 'selected':''}} >Accepté</option>
                                                        </select>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="done" {{$f->done == 1 ? 'checked':''}} {{$user->id == Auth::user()->id ? 'disabled':'' }} >
                                                    </td>
                                                    <td class="text-center"> 
                                                        @if($user->id == Auth::user()->id)
                                                        <a href="javascript:void(0)" onclick="return chmFormation.edit({e_id: {{$e->id}} , id: {{$f->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                        @else
                                                        <button type="submit" class="btn btn-sm btn-flat bg-navy">Mettre à jour</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                </form>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                                @endif
                                @if($user->id == Auth::user()->id)
                                <a onclick="return chmFormation.create()" data-id="{{$e->id}}" class="btn btn-success addBtn"><i class="fa fa-plus"></i> Demander une formation</a>
                                @endif
                                </div>
                            </div>
                            @if($user->id != Auth::user()->id && count($historiques)>0)
                            <div class="panel panel-info">
                                <div class="panel-heading text-center lead"> Historique </div>
                                <div class="panel-body">
                                    <div class="box-body table-responsive no-padding mb40">
                                        <table class="table table-hover table-striped text-center">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Exercice </th>
                                                    <th>Formation demandée</th>
                                                    <th>Entretien</th>
                                                    <th>Etat</th>
                                                    <th>Réalisé</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($historiques as $f)
                                                <tr>
                                                    <td> {{ Carbon\Carbon::parse($f->date)->format('d/m/Y')}} </td>
                                                    <td> {{$f->exercice}} </td>
                                                    <td> {{$f->title}} </td>
                                                    <td> {{$f->entretien->titre}} </td>
                                                    <td>
                                                        <select name="status" id="status" class="label-@if($f->status == 0)default @elseif($f->status == 1)danger @elseif($f->status == 2)success @endif" disabled="" >
                                                            <option value="0" {{$f->status == 0 ? 'selected':''}} >En attente</option>
                                                            <option value="1" {{$f->status == 1 ? 'selected':''}} >Refusé</option>
                                                            <option value="2" {{$f->status == 2 ? 'selected':''}} >Accepté</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="done" {{$f->done == 1 ? 'checked':''}} disabled >
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                <div class="callout callout-info">
                    <p class="">
                        <i class="fa fa-info-circle fa-2x"></i> 
                        <span class="content-callout">Cette page affiche Liste des formations demandées de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
                    </p>
                </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  