@extends('layouts.app')
@section('title', 'Questionnaires')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    @foreach (['danger', 'warning', 'success', 'info'] as $key)
                        @if(Session::has($key))
                            <div class="chm-alerts alert alert-info alert-white rounded">
                                <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
                                <div class="icon"><i class="fa fa-info-circle"></i></div>
                                <span> {!! Session::get($key) !!} </span>
                            </div>
                        @endif
                    @endforeach
                    <div class="box-header">
                        <h3 class="box-title">Liste des questionnaires <span class="badge">{{$surveys->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmSurvey.form({})" class="btn bg-maroon" title="" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    <p class="help-block">Ces questionnaires vont vous permettre de les utiliser pour l'évaluation annuelle.<br> Vous pouvez créer autant de questionnaires et allez vers la page des <a href="{{ url('entretiens/index') }}" target="_blank">entretiens</a> pour choisir quel questionnaire sera utilisé lors de l'évaluation.</p>
                    @if(count($surveys)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-strped table-inversed-blue">
                                <tr>
                                    <th>Titre</th>
                                    <th>Section</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                @foreach($surveys as $key => $survey)
                                <tr>
                                    <td> {{ $survey->title }}</td>
                                    <td> {{ is_numeric($survey->evaluation_id) ? App\Evaluation::find($survey->evaluation_id)->title : '' }}</td>
                                    <td> {{ $survey->type == 0 ? 'Standard':'Personnalisé' }}</td>
                                    <td> {{ $survey->description ? $survey->description : '---' }} </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" onclick="return chmSurvey.form({{{$survey->id}}})" class="btn-primary icon-fill" title="Modifier" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmGroupe.create({sid: {{$survey->id}}})" class="btn-warning icon-fill" title="Ajouter des groupes" data-toggle="tooltip"> <i class="fa fa-plus"></i> </a>
                                        <a href="{{ url('surveys/'.$survey->id.'/groupes') }}" class="btn-info icon-fill" title="Liste des groupes" data-toggle="tooltip"> <i class="fa fa-list"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmSurvey.show({id: {{$survey->id}} })" class="bg-navy icon-fill" title="Voir" data-toggle="tooltip"> <i class="fa fa-eye"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer le questionnaire ?', 'Etes-vous sur de vouloir supprimer ce questionnaire ?','chmSurvey.delete', {sid: {{$survey->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer"> <i class="fa fa-trash"></i> </a>
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
  