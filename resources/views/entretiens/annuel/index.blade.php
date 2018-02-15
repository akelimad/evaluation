@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Mes entretiens d'évaluations</h3>
                        <div class="box-tools">
                            
                        </div>
                    </div>
                    <p class="mentor"> Votre mentor est : <b>{{ $mentor->name }} {{ $mentor->last_name }}</b> ( <a href="mailto:{{$mentor->email}}">{{$mentor->email}}</a> ) </p>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Titre </th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Mentor</th>
                                    <th>RH</th>
                                    <th>Signé</th>
                                    <th>PDF</th>
                                    <th class="text-center"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entretiens as $e)
                                <tr>
                                    <td>
                                        @if($e->type == "annuel")
                                        <a href="{{ url('entretiens/evaluations/'.$e->id) }}">{{$e->titre}}</a>
                                        @else
                                            <a href="{{ url('entretiens/'.$e->type.'s/'.$e->id) }}">{{$e->titre}}</a>
                                        @endif
                                    </td>
                                    <td>{{$e->type}}</td>
                                    <td>{{$e->date}}</td>
                                    <td><span class="label label-danger empty"> </span></td>
                                    <td><span class="label label-danger empty"> </span></td>
                                    <td><span class="label label-danger empty"> </span></td>
                                    <td><span class="label label-danger empty"> </span></td>
                                    <td></td>
                                    <td class="text-center">
                                        <a href="" class="btn-primary icon-fill"> <i class="fa fa-eye"></i> </a>
                                        <a href="" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="alert alert-info">Aucune donnée disponible !</p>
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  