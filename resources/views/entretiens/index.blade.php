@extends('layouts.app')
@section('title', 'Entretiens')
@section('breadcrumb')
    <li>Entretiens</li>
@endsection
@section('content')
    <section class="content entretiens-list">
        <div class="row">
            <div class="col-md-12">
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
                    <p class="help-block"> Dans cette page vous allez pouvoir personnaliser le questionnaire à affecter pour la partie d'évaluation de l'entretien en question <br> Selectionnez le questionnaire et puis cliquez sur Actualiser. </p>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding mb40">
                        <table class="table table-hover table-striped table-inversed-blue">
                            <thead>
                                <tr>
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
                                        <td title="{{$e->titre}}">{{ str_limit($e->titre, 20) }}</td>
                                        @foreach($evaluations as $evaluation)
                                        <form action="{{ url('entretiens/storeEntretienEvals') }}" method="post">
                                        {{ csrf_field() }}
                                        <td>
                                            <input type="hidden" name="entretien_id" value="{{ $e->id }}">
                                            <div class="checkbox-item text-blue">
                                                <input type="checkbox" class="checkbox-eval" name="choix[{{$evaluation->id}}][evaluation_id]" value="{{ $evaluation->id }}" title="Cochez/decochez si vous voulez que {{ $evaluation->title }} soit visible/invisible lors l'évaluation de cette entretien" data-toggle="tooltip" {{ in_array($evaluation->id, $e->evaluations->pluck('id')->toArray()) ? 'checked': '' }}>
                                            </div>
                                            @if($evaluation->title == "Evaluations")
                                            <select name="choix[{{$evaluation->id}}][survey_id]" class="form-control surveySelect" title="Questionnaire de l'évaluation" data-toggle="tooltip" style="background: none;border-color: #ece8e8;">
                                                <option value="" {{ !$e->survey_id ? 'selected':'' }}></option>
                                                @foreach(App\Survey::getAll()->where('evaluation_id', 1)->get() as $s)
                                                <option value="{{$s->id}}" {{App\Evaluation::surveyId($e->id, $evaluation->id) == $s->id? 'selected':''}}>{{$s->title}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @if($evaluation->title == "Carrières")
                                            <select name="choix[{{$evaluation->id}}][survey_id]" class="form-control surveySelect" title="Questionnaire de la carrière" data-toggle="tooltip" style="background: none;border-color: #ece8e8;">
                                                <option value="" {{ !$e->survey_id ? 'selected':'' }}></option>
                                                @foreach(App\Survey::getAll()->where('evaluation_id', 2)->get() as $s)
                                                <option value="{{$s->id}}"  {{App\Evaluation::surveyId($e->id, $evaluation->id) == $s->id? 'selected':''}}>{{$s->title}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @if($evaluation->title == "Objectifs")
                                            <select name="choix[{{$evaluation->id}}][survey_id]" class="form-control surveySelect" title="" data-toggle="tooltip" style="background: none;border-color: #ece8e8;">
                                                <option value="" {{ !$e->survey_id ? 'selected':'' }}></option>
                                                @foreach($objectifs as $o)
                                                <option value="{{$o->id}}"  {{App\Evaluation::surveyId($e->id, $evaluation->id) == $o->id? 'selected':''}}>{{$o->title}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </td>
                                        @endforeach
                                        <td class="text-center">
                                            {{ csrf_field() }}
                                            <div class="btn-group">
                                                <button aria-expanded="false" aria-haspopup="true" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <button type="submit" class="form-control no-border text-left pl-20"><i class="fa fa-refresh"></i> Mettre à jour</button>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="return chmEntretien.show({{{$e->id}}})" class=""><i class="fa fa-eye"></i> Voir</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="return chmEntretien.form({{{$e->id}}})" class=""><i class="fa fa-pencil"></i> Modifier</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('entretiens/'.$e->id.'/notation/download') }}" class=""><i class="fa fa-download"></i> Télécharger la notation</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer l\'entretien ?', 'Etes-vous sur de vouloir supprimer cet entretien ?','chmEntretien.delete', {eid: {{$e->id}} }, {width: 450})" class="" ><i class="fa fa-trash"></i> Supprimer</a>
                                                    </li>
                                                </ul>
                                            </div>
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

@section('javascript')
<script>
    $(document).ready(function() {
        $('.table-responsive').on('show.bs.dropdown', function () {
            console.log('show')
            $('.table-responsive').css('overflow', 'inherit')
        })
        $('.table-responsive').on('hide.bs.dropdown', function () {
            console.log('hide')
            $('.table-responsive').css('overflow', 'auto')
        })

        $('.checkbox-eval').change(function() {
            if(this.checked) {
                $(this).closest('td').find('select').prop('required', true)
            } else {
                $(this).closest('td').find('select').prop('required', false)
            }
        })
        $('.checkbox-eval').change()
    })
</script>
@endsection