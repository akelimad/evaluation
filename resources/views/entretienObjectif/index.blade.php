@extends('layouts.app')
@section('breadcrumb')
  <li>Objectifs</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des objectifs <span class="badge">{{$objectifs->total()}}</span></h3>

            <div class="box-tools mb40">
              <a href="{{ route('config.objectifs.form') }}" class="btn bg-maroon"><i class="fa fa-plus"></i>Ajouter</a>
            </div>
          </div>
          @if(count($objectifs)>0)
            <div class="box-body table-responsive no-padding mb40">
              <table class="table table-hover table-striped table-inversed-blue">
                <tr>
                  <th>Type</th>
                  <th>Equipe</th>
                  <th>Titre</th>
                  <th>Description</th>
                  <th>Date d'échéance</th>
                  <th class="text-center">Actions</th>
                </tr>
                @foreach($objectifs as $key => $objectif)
                  <tr>
                    <td>{{ $objectif->type }}</td>
                    <td>{{ $objectif->team > 0 ? \App\Team::find($objectif->team)->name : '---' }}</td>
                    <td title="{{ $objectif->title }}">{{ $objectif->title ? str_limit($objectif->title, 30) : '' }}</td>
                    <td title="{{ $objectif->description }}">{{ $objectif->description ? str_limit($objectif->description, 50) : '---' }}</td>
                    <td>{{ date('d/m/Y', strtotime($objectif->deadline)) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a href="javascript:void(0)" onclick="return chmEntretienObjectif.show({id: {{$objectif->id}} })" class=""> <i class="fa fa-eye"></i> Visualiser</a>
                          </li>
                          <li>
                            <a href="{{ route('config.objectifs.form', ['id' => $objectif->id]) }}" class=""><i class="fa fa-edit"></i> Modifier</a>
                          </li>
                          <li>
                            <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer l\'objectif ?', 'Etes-vous sur de vouloir supprimer cet objectif ?','chmEntretienObjectif.delete', {id: {{$objectif->id}} }, {width: 450})" class=""> <i class="fa fa-trash"></i> Supprimer</a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </table>
              {{ $objectifs->links() }}
            </div>
          @else
            @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
  