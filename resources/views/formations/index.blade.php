@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des formations </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id)}}">Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/activites')}}">Activités</a></li>
                            @if($e->type == "annuel")
                            <li ><a href="{{url('entretiens/'.$e->id.'/skills')}}">Compétences</a></li>
                            <li ><a href="{{url('entretiens/'.$e->id.'/objectifs')}}">Objectifs</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/remunerations')}}">Rémunérations</a></li>
                            @endif
                            <li class="active"><a href="#">Formations</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/documents')}}">Documents</a></li>
                            @if($e->type == "professionnel")
                            <li><a href="{{url('entretiens/'.$e->id.'/decisions')}}">Décisions</a></li>
                            @endif
                            <li><a href="{{url('entretiens/'.$e->id.'/comments')}}">Commentaire</a></li>
                        </ul>
                        <div class="tab-content">
                            @if(count($formations)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead>
                                            <tr>
                                              <th>Titre</th>
                                              <th>Trimestre </th>
                                              <th>Envie de transmettre</th>
                                              <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($formations as $f)
                                            <tr>
                                                <td> {{$f->titre}} </td>
                                                <td> {{$f->date}} </td>
                                                <td>
                                                    @if($f->transmit == 0)
                                                        <span class="label label-danger">Non</span>
                                                    @else
                                                        <span class="label label-success">Oui</span>
                                                    @endif
                                                </td>
                                                <td> 
                                                    <a href="" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                                    <a href="javascript:void(0)" onclick="return chmFormation.edit({e_id: {{$e->id}} , id: {{$f->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
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
                            <a onclick="return chmFormation.create()" data-id="{{$e->id}}" class="btn btn-success addBtn">Ajouter une formation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  