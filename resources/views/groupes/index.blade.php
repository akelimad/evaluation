@extends('layouts.app')
@section('title', 'Groupes')
@section('breadcrumb')
    <li>Questionnaires</li>
    <li>{{ $survey->title }}</li>
    <li>Groupes</li>
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
                        <h3 class="box-title">Liste des groupes <span class="badge">{{$groupes->total()}}</span> du questionnaire: <i><b>{{ $survey->title }}</b></i></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmGroupe.create({sid: {{$sid}}})" class="btn bg-maroon" data-toggle="tooltip" title="Nouveau type de questions"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($groupes)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th>Type de questions</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                @foreach($groupes as $key => $g)
                                <tr>
                                    <td> {{ $g->name }} </td>
                                    <td> {{ $g->description }} </td>
                                    <td class="text-center"> 
                                        {{ csrf_field() }} 
                                        <a href="javascript:void(0)" onclick="return chmGroupe.edit({sid: {{$survey->id}} ,gid: {{$g->id}} })" class="btn-warning icon-fill" data-toggle="tooltip" title="Modifier le type de questions"> <i class="glyphicon glyphicon-pencil"></i> </a>

                                        <a href="javascript:void(0)" onclick="return chmQuestion.create({sid: {{$sid}} ,gid: {{$g->id}} })" class="btn-info icon-fill" data-toggle="tooltip" title="Ajouter les questions pour ce type"> <i class="fa fa-plus"></i> </a>

                                        <a href="{{ url('surveys/'.$sid.'/groupes/'.$g->id.'/questions') }}" class="btn-info icon-fill" data-toggle="tooltip" title="lister les questions"> <i class="fa fa-list"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer ce type de questions ?', 'Etes-vous sur de vouloir supprimer ce type de questions ?','chmGroupe.delete', {sid: {{$sid}} ,gid: {{$g->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer le type de questions"> <i class="fa fa-trash"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $groupes->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif

                    <a href="{{ url('config/surveys') }}" class="btn btn-default" data-toggle="tooltip"><i class="fa fa-long-arrow-left"></i> Retour</a>
                </div>
            </div>
        </div>
    </section>
@endsection
  