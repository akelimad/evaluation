@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                
                <div class="box box-primary">
                    <h3 class="alert alert-info">
                        <a href="{{ url('surveys') }}"><i class="fa fa-long-arrow-left"></i> Retour</a>
                    </h3>
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
                        <h3 class="box-title">La liste des groupes <span class="badge">{{$groupes->total()}}</span> du questionnaire: {{ $survey->title }}</h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmGroupe.create({sid: {{$sid}}})" class="btn bg-maroon" data-toggle="tooltip" title="Ajouter un groupe"> <i class="fa fa-user-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($groupes)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered table-inversed-blue">
                                <tr>
                                    <th>Id</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                @foreach($groupes as $key => $g)
                                <tr>
                                    <td> {{ $key+1 }} </td>
                                    <td> {{ $g->name }} </td>
                                    <td> {{ $g->description }} </td>
                                    <td class="text-center">  
                                        <a href="javascript:void(0)" onclick="return chmGroupe.edit({id: {{$g->id}} })" class="btn-warning icon-fill" data-toggle="tooltip" title="Modifier le groupe"> <i class="glyphicon glyphicon-pencil"></i> </a>

                                        <a href="javascript:void(0)" onclick="return chmQuestion.create({sid: {{$sid}} ,gid: {{$g->id}} })" class="btn-info icon-fill" data-toggle="tooltip" title="Ajouter les questions au groupe"> <i class="fa fa-question"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $groupes->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  