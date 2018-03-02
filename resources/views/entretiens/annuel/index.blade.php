@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des entretiens d'évaluations</h3>
                        <div class="box-tools">
                            
                        </div>
                    </div>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Date </th>
                                    <th>Fonction</th>
                                    <th>Type d'eval</th>
                                    <th>Réf</th>
                                    <th>Mentor</th>
                                    <th>Fonction</th>
                                    <th>Auto eval</th>
                                    <th>Visa N+1</th>
                                    <th>Visa N+1</th>
                                    <th class="text-center"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entretiens as $entretien)
                                    @foreach($entretien->users as $user)
                                    <tr class="text-center userName">
                                        <td colspan="10"> <b> {{ $user->name. ' '.$user->last_name  }} </b> </td>
                                    </tr>
                                    <tr>
                                        <td class="text-blue">{{ Carbon\Carbon::parse($entretien->date)->format('d/m/Y')}}</td>
                                        <td>{{$user->function}}</td>
                                        <td>{{$entretien->titre}}</td>
                                        <td>{{$entretien->id}}</td>
                                        <td>{{$user->parent ? $user->parent->name.' '. $user->parent->last_name : '---'}}</td>
                                        <td>{{$user->parent ? $user->parent->function : '---'}}</td>
                                        <td><span class="label label-danger"> Non </span></td>
                                        <td><span class="label label-danger"> Non </span></td>
                                        <td><span class="label label-danger"> Non </span></td>
                                        <td class="text-center">
                                            <a href="" class="btn-primary icon-fill"> <i class="fa fa-print"></i> </a>
                                            <a href="javascript:void(0)" class="btn-warning icon-fill" data-toggle="tooltip" data-placement="top" title="Motif de non réaliation"> <i class="glyphicon glyphicon-wrench"></i> </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="alert alert-default">Aucune donnée disponible !</p>
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  