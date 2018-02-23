@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des rémunérations </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id)}}">Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/activites')}}">Activités</a></li>
                            @if($e->type == "annuel")
                            <li ><a href="{{url('entretiens/'.$e->id.'/skills')}}">Compétences</a></li>
                            <li ><a href="{{url('entretiens/'.$e->id.'/objectifs')}}">Objectifs</a></li>
                            <li class="active"><a href="#">Rémunérations</a></li>
                            @endif
                            <li><a href="{{url('entretiens/'.$e->id.'/formations')}}">Formations</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/documents')}}">Documents</a></li>
                            @if($e->type == "professionnel")
                            <li><a href="{{url('entretiens/'.$e->id.'/decisions')}}">Décisions</a></li>
                            @endif
                            <li><a href="{{url('entretiens/'.$e->id.'/comments')}}">Commentaire</a></li>
                        </ul>
                        <div class="tab-content">
                            @if(count($remunerations)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <table class="table table-hover table-bordered text-center">
                                        <thead>
                                            <tr>  
                                                <th>type </th>
                                                <th>Montant</th>
                                                <th>Raison</th>
                                                <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($remunerations as $r)
                                            <tr>
                                                <td> {{$r->type}} </td>
                                                <td> {{$r->amount}} </td>
                                                <td> {{$r->reason}} </td>
                                                <td> 
                                                    <a href="" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                                    <a href="javascript:void(0)" onclick="return chmRemuneration.edit({e_id: {{$e->id}} , id: {{$r->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
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
                            <a onclick="return chmRemuneration.create()" data-id="{{$e->id}}" class="btn btn-success addBtn">Ajouter une rémunération</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  