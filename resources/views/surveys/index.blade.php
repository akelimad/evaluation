@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des questionnaires <span class="badge">{{$surveys->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmSurvey.create()" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($surveys)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered table-inversed-blue">
                                <tr>
                                    <th>Id</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                @foreach($surveys as $key => $survey)
                                <tr>
                                    <td> {{ $survey->id }}</td>
                                    <td> {{ $survey->title }}</td>
                                    <td> {{ $survey->description }} </td>
                                    <td class="text-center">  
                                        <a href="javascript:void(0)" onclick="return chmSurvey.edit({sid: {{$survey->id}}})" class="btn-primary icon-fill" title="Modifier ce questionnaire" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmGroupe.create({sid: {{$survey->id}}})" class="btn-primary icon-fill" title="Ajouter un groupe pour ce questionnaire" data-toggle="tooltip"> <i class="fa fa-plus"></i> </a>
                                        <a href="{{ url('surveys/'.$survey->id.'/groupes') }}" class="btn-primary icon-fill" title="Liste des groupes" data-toggle="tooltip"> <i class="fa fa-list"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $surveys->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  