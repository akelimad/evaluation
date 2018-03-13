@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des objectifs </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}">Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/evaluations')}}">Evaluations</a></li>
                            <li><a href="">Carrieres</a></li>
                            <li><a href="">Formations</a></li>
                            <li><a href="">Competences</a></li>
                            <li class="active"><a href="#">Objectifs</a></li>
                            <li><a href="">Salaires</a></li>
                            <li><a href="">Commentaires</a></li>
                        </ul>
                        <div class="tab-content">
                            @if(count($objectifs)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>Titre</th>
                                                <th>Description </th>
                                                <th>Notation</th>
                                                <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($objectifs as $o)
                                            <tr>
                                                <td> {{$o->titre}} </td>
                                                <td> {{$o->description}} </td>
                                                <td>
                                                    <table class="table" style="margin-bottom: 0">
                                                        <tr>
                                                            <td>N-1</td>
                                                            <td>Réalisé</td>
                                                            <td>Ecart</td>
                                                            <td>N+1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>10</td>
                                                            <td>0</td>
                                                            <td>-10</td>
                                                            <td> </td>
                                                        </tr>
                                                        <div class="range-slider">
                                                          <input class="range-slider__range" type="range" value="100" min="0" max="500">
                                                          <span class="range-slider__value">0</span>
                                                        </div>
                                                    </table>
                                                </td>
                                                <td> 
                                                    <a href="" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                                    <a href="javascript:void(0)" onclick="return chmObjectif.edit({e_id: {{$e->id}} , id: {{$o->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="alert alert-default">Aucune donnée disponible !</p>
                            @endif
                            <a href="{{url('/')}}" class="btn btn-default">Revenir à la liste</a>
                            <a onclick="return chmObjectif.create()" data-id="{{$e->id}}" class="btn btn-success addBtn">Ajouter un objectif</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  