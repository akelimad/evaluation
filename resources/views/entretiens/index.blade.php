@extends('layouts.app')
@section('content')
    <section class="content entretiens-list">
        <div class="row">
            <div class="col-md-12">
                {{--@if (Session::has('success_evaluations_save'))--}}
                    {{--@include('partials.alerts.success', ['messages' => Session::get('success_evaluations_save') ])--}}
                {{--@endif--}}
                {{--@if (Session::has('incompleteSurvey'))--}}
                    {{--@include('partials.alerts.warning', ['messages' => Session::get('incompleteSurvey') ])--}}
                {{--@endif--}}

                    @foreach (['danger', 'warning', 'success', 'info'] as $key)
                        @if(Session::has($key))
                            @include('partials.alerts.'.$key, ['messages' => Session::get($key) ])
                        @endif
                    @endforeach

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Liste des entretiens <span class="badge">{{$entretiens->total()}}</span></h3>
                        <div class="box-tools">
                            <a href="javascript:void(0)" onclick="return chmEntretien.form({})" class="btn bg-maroon" data-toggle="tooltip" title="Créer un entretien"> <i class="fa fa-plus"></i> Ajouter</a>
                        </div>
                    </div>
                    <p class="help-block"> Dans cette page vous allez pouvoir personnaliser le questionnaire à affecter pour la partie d'évaluation de l'entretien en question ansi que pour la partie des objectifs. <br> Selectionnez le questionnaire et l'objectif puis cliquer sur Actualiser. </p>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding mb40">
                        <table class="table table-hover table-striped text-center table-inversed-blue">
                            <thead>
                                <tr>
                                    <th>Id </th>
                                    <th>Type</th>
                                    @foreach($evaluations as $evaluation)
                                    <th> {{ $evaluation->title }} </th>
                                    @endforeach
                                    <th class="text-center">Actions</th>
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
                                            @if($evaluation->title == "Evaluations")
                                            <select name="entretiens[{{$e->id}}][]" id="surveySelect" class="form-control" title="Choisissez le questionnaire qui sera affiché pour cette evaluation" data-toggle="tooltip" required style="background: none;border-color: #ece8e8">
                                                <option value="">== Choisissez ==</option>
                                                @foreach($surveys as $survey)
                                                <option value="{{$survey->id}}" {{ $survey->id == $e->survey_id ? 'selected':'' }} >{{$survey->title}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @if($evaluation->title == "Objectifs")
                                            <select name="entretiens[{{$e->id}}][]" id="surveySelect" class="form-control" title="Choisissez l'objectif qui sera affiché pour cette evaluation" data-toggle="tooltip">
                                                <option value="">== Choisissez ==</option>
                                                @foreach($objectifs as $obj)
                                                <option value="{{$obj->id}}" {{ $obj->id == $e->objectif_id ? 'selected':'' }} >{{$obj->title}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </td>
                                        @endforeach
                                        <td class="text-center">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn-success icon-fill" data-toggle="tooltip" title="Mettre à jour"><i class="fa fa-refresh"></i></button>
                                            <a href="javascript:void(0)" onclick="return chmEntretien.form({{{$e->id}}})" class="btn-warning icon-fill" data-toggle="tooltip" title="Modifier"> <i class="fa fa-pencil"></i></a>
                                            <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer l\'entretien ?', 'Etes-vous sur de vouloir supprimer cet entretien ?','chmEntretien.delete', {eid: {{$e->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer l'entretien"> <i class="fa fa-trash"></i> </a>
                                            <div class="clearfix"></div>
                                        </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $entretiens->links() }}
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  