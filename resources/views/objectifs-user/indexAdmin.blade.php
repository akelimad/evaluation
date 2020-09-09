@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Liste des objectifs entretiens <span class="badge">{{$objectifs->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="chmObjectif.create({oid: {{$oid}}})" class="btn bg-maroon" title="Ajouter une section" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if( $count > 0 )
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue mb40">
                                <tr>
                                    <th style="width: 25%">section</th>
                                    <th style="width: 55%">Titre</th>
                                    <th style="width: 10%">Pondération (%)</th>
                                    <th style="width: 10%" class="">Actions</th>
                                </tr>
                                @foreach($objectifs as $key => $objectif)
                                    @if( count($objectif->children)>0 )
                                        <tr>
                                            {{ csrf_field() }}
                                            <td> {{ $objectif->title }} </td>
                                            <td></td>
                                            <td></td>
                                            <td class="">
                                                <a href="javascript:void(0)" onclick="return chmObjectif.edit({oid: {{$oid}}, gid: {{$objectif->id}}})" class="btn-warning icon-fill" title="Modifier les sections d'objectif" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer les objectifs ?', 'Etes-vous sur de vouloir supprimer les objectifs de cette section ?','chmObjectif.delete', {oid: {{$oid}} , gid: {{$objectif->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer les objectifs de cette section"> <i class="fa fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                        @foreach( $objectif->children as $key => $sub )
                                        <tr>
                                            <td></td>
                                            <td>
                                              {{ $sub->title ? $sub->title : '---' }}
                                              @if (count($sub->children) > 0)
                                                <a href="javascript:void(0)" onclick="return chmObjectif.subObjectifForm({oid: {{$oid}}, gid: {{$objectif->id}},subObjId: {{$sub->id}}})" title="Contient des sous objectifs"> <i class="fa fa-info-circle"></i></a>
                                              @endif
                                            </td>
                                            <td> {{ $sub->ponderation ? $sub->ponderation : '---' }}  </td>
                                            <td class="">
                                              <a href="javascript:void(0)" onclick="return chmObjectif.subObjectifForm({oid: {{$oid}}, gid: {{$objectif->id}},subObjId: {{$sub->id}}})" class="btn-warning icon-fill" title="Ajouter ou modifier les sous sections d'objectif (Optionnel)" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </table>
                            {{ $objectifs->links() }}
                            <a href="{{ url('config/entretienObjectif') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  