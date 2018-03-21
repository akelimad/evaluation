@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                @if(Session::has('update_formation'))
                    @include('partials.alerts.success', ['messages' => Session::get('update_formation') ])
                @endif
                    <h3 class="mb40"> La liste des formations</h2>
                    <div class="callout callout-info">
                        <p class="">
                            <i class="fa fa-info-circle fa-2x"></i> 
                            <span class="content-callout">Cette page affiche la liste des formations demandées de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
                        </p>
                    </div>
                    <div class="nav-tabs-custom">
                        @include('partials.tabs')
                        <div class="tab-content">
                            @if(count($formations)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Exercice </th>
                                                <th>Formation demandée</th>
                                                <th>Etat</th>
                                                <th>Réalisé</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($formations as $f)
                                            <form action="{{url('entretiens/formations/'.$f->id.'/mentorUpdate')}}" method="post">
                                                <input type="hidden" name="_method" value="PUT">
                                            {{ csrf_field() }}
                                            <tr>
                                                <td> {{$f->date}} </td>
                                                <td> {{$f->exercice}} </td>
                                                <td> {{$f->title}} </td>
                                                <td>
                                                    <select name="status" id="status" class="label label-@if($f->status == 0)default @elseif($f->status == 1)danger @elseif($f->status == 2)success @endif" {{$user->id == Auth::user()->id ? 'disabled':'' }} >
                                                        <option value="0" {{$f->status == 0 ? 'selected':''}} >En attente</option>
                                                        <option value="1" {{$f->status == 1 ? 'selected':''}} >Refusé</option>
                                                        <option value="2" {{$f->status == 2 ? 'selected':''}} >Accepté</option>
                                                    </select>
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
                                <p class="alert alert-default">Aucune donnée disponible !</p>
                            @endif
                            <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Anuuler</a>
                            @if($user->id == Auth::user()->id)
                            <a onclick="return chmFormation.create()" data-id="{{$e->id}}" class="btn btn-success addBtn"><i class="fa fa-plus"></i> Demander une formation</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  