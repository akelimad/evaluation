@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des compétences </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id)}}">Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/activites')}}">Parcours</a></li>
                            @if($e->type == "annuel")
                            <li class="active"><a href="#">Compétences</a></li>
                            <li ><a href="{{url('entretiens/'.$e->id.'/objectifs')}}">Objectifs</a></li>
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
                            @if(count($skills)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead>
                                            <tr>
                                              <th>Domaine</th>
                                              <th>Nom </th>
                                              <th>Niveau</th>
                                              <th>Transmettre</th>
                                              <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($skills as $s)
                                            <tr>
                                                <td> {{$s->domaine}} </td>
                                                <td> {{$s->titre}} </td>
                                                <td>
                                                    @if($s->niveau == 1) Connaissance de base
                                                    @elseif($s->niveau == 2) Maîtrise
                                                    @elseif($s->niveau == 3) Maîtrise avancée
                                                    @elseif($s->niveau == 4) Expert
                                                    @elseif($s->niveau == 5) Expert avancé
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($s->transmit == 1)
                                                        <span class="label label-success"> oui </span>
                                                    @else
                                                        <span class="label label-danger"> Non </span>
                                                    @endif
                                                </td>
                                                <td> 
                                                    <a href="" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                                    <a href="javascript:void(0)" onclick="return chmSkill.edit({e_id: {{$e->id}} , id: {{$s->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
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
                            <a onclick="return chmSkill.create()" data-id="{{$e->id}}" class="btn btn-success addBtn">Ajouter une compétence</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  