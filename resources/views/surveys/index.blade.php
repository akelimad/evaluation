@extends('layouts.app')
@section('title', 'Questionnaires')
@section('breadcrumb')
  <li>Questionnaires</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if(Session::has($key))
              <div class="chm-alerts alert alert-{{$key}} alert-white rounded">
                <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
                <div class="icon"><i class="fa fa-info-circle"></i></div>
                <span> {!! Session::get($key) !!} </span>
              </div>
            @endif
          @endforeach
          <div class="box-header">
            <h3 class="box-title">Liste des questionnaires <span class="badge">{{$surveys->total()}}</span></h3>
            <div class="box-tools mb40">
              <a href="{{ route('survey.form') }}" class="btn bg-maroon" title="" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
            </div>
          </div>
          <p class="help-block">Ces questionnaires vont vous permettre de les utiliser pour l'évaluation annuelle.<br> Vous pouvez créer autant de questionnaires et allez vers la page des <a href="{{ url('entretiens/index') }}" target="_blank">entretiens</a> pour choisir quel questionnaire sera utilisé lors de l'évaluation.</p>
          @if(count($surveys)>0)
            <div class="box-body table-responsive no-padding mb40">
              <table class="table table-hover table-strped table-inversed-blue">
                <tr>
                  <th>Titre</th>
                  <th>Modèle</th>
                  <th>Section</th>
                  <th>Description</th>
                  <th class="text-center">Actions</th>
                </tr>
                @foreach($surveys as $key => $survey)
                  <tr>
                    <td>{{ $survey->title }}</td>
                    <td>{{ $survey->model or '---' }}</td>
                    <td>{{ $survey->evaluation_id > 0 ? App\Evaluation::findOrFail($survey->evaluation_id)->title : '---' }}</td>
                    <td> {{ $survey->description ? $survey->description : '---' }} </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a href="javascript:void(0)" onclick="return chmSurvey.show({id: {{$survey->id}} })" class=""><i class="fa fa-eye"></i> Visualiser</a>
                          </li>
                          <li>
                            <a href="{{ route('survey.form', ['id' => $survey->id]) }}" class=""><i class="fa fa-edit"></i> Modifier</a>
                          </li>
                          <li>
                            <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer le questionnaire ?', 'Etes-vous sur de vouloir supprimer ce questionnaire ?','chmSurvey.delete', {sid: {{$survey->id}} }, {width: 450})" class=""><i class="fa fa-trash"></i> Supprimer</a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </table>
              {{ $surveys->links() }}
            </div>
          @else
            @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection