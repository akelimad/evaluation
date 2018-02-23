@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des activités </h3>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id)}}">Synthèse</a></li>
                            <li class="active"><a href="#">Activités</a></li>
                            @if($e->type == "annuel")
                            <li><a href="{{url('entretiens/'.$e->id.'/skills')}}">Compétences</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/objectifs')}}">Objectifs</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/remunerations')}}">Rémunérations</a></li>
                            @endif
                            <li><a href="{{url('entretiens/'.$e->id.'/formations')}}">Formations</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/documents')}}">Documents</a></li>
                            @if($e->type == "professionnel")
                            <li><a href="{{url('entretiens/'.$e->id.'/decisions')}}">Décisions</a></li>
                            @endif
                            <li><a href="{{url('entretiens/'.$e->id.'/comments')}}">Commentaire</a></li>
                        </ul>
                        <div class="tab-content">
                            @if(count($activites)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                              <th>Titre</th>
                                              <th>Client </th>
                                              <th>Durée</th>
                                              <th>Evaluation</th>
                                              <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activites as $a)
                                            <tr>
                                                <td> {{$a->titre}} </td>
                                                <td> {{$a->client}} </td>
                                                <td> {{$a->duree}} </td>
                                                <td>
                                                    @if($a->evaluation == 1) Insatisfaisant
                                                    @elseif($a->evaluation == 2) Peu satisfaisant
                                                    @elseif($a->evaluation == 3) Satisfaisant
                                                    @elseif($a->evaluation == 4) Assez satisfaisant
                                                    @elseif($a->evaluation == 5) Très satisfaisant
                                                    @endif
                                                </td>
                                                <td class="text-center"> 
                                                    <a href="" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                                    <a href="javascript:void(0)" onclick="return chmActivite.edit({e_id: {{$e->id}} , id: {{$a->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="alert alert-default">Aucune donnée disponible !</p>
                            @endif
                            <a href="{{url('/')}}" class="btn btn-default ">Revenir à la liste</a>
                            <a onclick="return chmActivite.create()" data-id="{{$e->id}}" class="btn btn-success addBtn">Ajouter une activité</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  