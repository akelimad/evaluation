@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h2 class="mb40"> La liste des activités </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/evaluations/1')}}">Synthèse</a></li>
                            <li class="active"><a href="{{url('entretiens/activites')}}">Parcours</a></li>
                            <li><a href="#">Compétences</a></li>
                            <li><a href="#">Objectifs</a></li>
                            <li><a href="#">Formations</a></li>
                            <li><a href="#">Supports annexes</a></li>
                            <li><a href="#">Documents</a></li>
                            <li><a href="#">Rémunérations</a></li>
                            <li><a href="#">Notes</a></li>
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
                                                <td> {{$a->evaluation}} </td>
                                                <td class="text-center"> 
                                                    <a href="" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                                    <a href="{{url('entretiens/activites/'.$a->id.'/edit')}}" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="alert alert-info">Aucune donnée disponible !</p>
                            @endif
                            <a href="{{url('entretiens')}}" class="btn btn-default btn-flat">Revenir à la liste</a>
                            <a href="{{url('entretiens/activites/create')}}" class="btn btn-primary btn-flat">Ajouter une activité</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  