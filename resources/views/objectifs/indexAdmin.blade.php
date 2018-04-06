@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des objectifs entretiens <span class="badge">{{$objectifs->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="chmObjectif.create({oid: {{$oid}}})" class="btn bg-maroon" title="Ajouter une compétence" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if( $count > 0 )
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue mb40">
                                <tr>
                                    <th style="width: 25%">section</th>
                                    <th style="width: 55%">Titre</th>
                                    <th style="width: 10%">Ponderation</th>
                                    <th style="width: 10%" class="text-center">Action</th>
                                </tr>
                                @foreach($objectifs as $key => $objectif)
                                    @if( count($objectif->children)>0 )
                                        <tr>
                                            {{ csrf_field() }}
                                            <td> {{ $objectif->title }} </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">  
                                                <a href="javascript:void(0)" onclick="return chmObjectif.edit({oid: {{$objectif->id}}})" class="btn-warning icon-fill" title="Modifier les sections d'objectif" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer les objectifs ?', 'Etes-vous sur de vouloir supprimer les objectifs de cette section ?','chmObjectif.delete', {oid: {{$objectif->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer les objectifs de cette section"> <i class="fa fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                        @foreach( $objectif->children as $key => $sub )
                                        <tr>
                                            <td></td>
                                            <td> {{ $sub->title ? $sub->title : '---' }}</td>
                                            <td> {{ $sub->ponderation ? $sub->ponderation : '---' }}  </td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </table>
                            {{ $objectifs->links() }}
                            <a href="{{ url('entretienObjectif') }}" class="btn btn-default"> Retour </a>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  