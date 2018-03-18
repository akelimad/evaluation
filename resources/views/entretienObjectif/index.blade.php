@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des objectifs entretien <span class="badge">{{$objectifs->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmEntretienObjectif.create()" class="btn bg-maroon" title="Ajouter un objectif standard ou personnalisé" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($objectifs)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered table-inversed-blue">
                                <tr>
                                    <th>Id</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                @foreach($objectifs as $key => $objectif)
                                <tr>
                                    <td> {{ $objectif->id }}</td>
                                    <td> {{ $objectif->title }}</td>
                                    <td> {{ $objectif->description ? $objectif->description : '---' }} </td>
                                    <td class="text-center">  
                                        <a href="javascript:void(0)" onclick="return chmEntretienObjectif.edit({sid: {{$objectif->id}}})" class="btn-primary icon-fill" title="Modifier ce questionnaire" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmObjectif.create({oid: {{$objectif->id}}})" class="btn-warning icon-fill" title="Ajouter des section d'objectifs" data-toggle="tooltip"> <i class="fa fa-plus"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmEntretienObjectif.show({id: {{$objectif->id}} })" class="btn-info icon-fill" title="preview" data-toggle="tooltip"> <i class="fa fa-eye"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $objectifs->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  