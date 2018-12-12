@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Liste des objectifs entretien<span class="badge">{{$objectifs->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmEntretienObjectif.create()" class="btn bg-maroon" title="Ajouter un objectif standard ou personnalisé" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($objectifs)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue">
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
                                        {{ csrf_field() }}  
                                        <a href="javascript:void(0)" onclick="return chmEntretienObjectif.edit({sid: {{$objectif->id}}})" class="btn-primary icon-fill" title="Modifier" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmObjectif.create({oid: {{$objectif->id}}})" class="btn-warning icon-fill" title="Ajouter des sections" data-toggle="tooltip"> <i class="fa fa-plus"></i> </a>
                                        <a href="{{ url('entretienObjectif/'.$objectif->id.'/groupes') }}" class="btn-info icon-fill" title="Lister les sections" data-toggle="tooltip"> <i class="fa fa-list"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmEntretienObjectif.show({id: {{$objectif->id}} })" class="bg-navy icon-fill" title="Voir" data-toggle="tooltip"> <i class="fa fa-eye"></i> </a>
                                        <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer l\'objectif ?', 'Etes-vous sur de vouloir supprimer cet objectif ?','chmEntretienObjectif.delete', {id: {{$objectif->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer"> <i class="fa fa-trash"></i> </a>
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
  