@extends('layouts.app')
@section('content')
    <section class="content entretiens-list">
        <div class="row">
            <div class="col-md-12">
                @if (Session::has('success_evaluations_save'))
                    @include('partials.alerts.success', ['messages' => Session::get('success_evaluations_save') ])
                @endif
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des entretiens</h3>
                        <div class="box-tools">
                            
                        </div>
                    </div>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id </th>
                                        <th>Type</th>
                                        @foreach($evaluations as $evaluation)
                                        <th> {{ $evaluation->title }} </th>
                                        @endforeach
                                        <th class="text-center"> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entretiens as $e) 
                                        <tr>
                                            <td>{{ $e->id }}</td>
                                            <td>{{ $e->titre }}</td>
                                            @foreach($evaluations as $evaluation)
                                            <form action="{{ url('entretiens/storeEntretienEvals') }}" method="post">
                                            {{ csrf_field() }}
                                            <td>
                                                <input type="hidden" name="entretien_id" value="{{ $e->id }}">
                                                <div class="checkbox-item text-blue">
                                                    <input type="checkbox" name="choix[{{$evaluation->id}}][evaluation_id]" value="{{ $evaluation->id }}" title="Cochez/decochez si vous voulez que {{ $evaluation->title }} soit visible/invisible lors l'évaluation de cette entretien" data-toggle="tooltip" {{ in_array($evaluation->id, $e->evaluations->pluck('id')->toArray()) ? 'checked': '' }}>
                                                </div>
                                                <select name="choix[{{$evaluation->id}}][survey_id]" id="surveySelect" class="form-control" title="Choisissez le questionnaire qui sera affiché pour cette evaluation" data-toggle="tooltip">
                                                    <option value="">Quest.</option>
                                                    @foreach($surveys as $survey)
                                                    <option value="{{$survey->id}}" {{ $survey->id == $evaluation->survey_id ? 'selected':'' }} >{{$survey->title}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            @endforeach
                                            <td class="text-center">
                                                <button type="submit" class="btn btn-success pull-right">
                                                    <i class="fa fa-check"></i> Ok
                                                </button>
                                                <div class="clearfix"></div>
                                            </td>
                                            </form>
                                        </tr>
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
  