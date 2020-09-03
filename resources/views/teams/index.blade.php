@extends('layouts.app')
@section('title', 'Equipes')
@section('breadcrumb')
  <li>Equipes</li>
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
            <h3 class="box-title">Liste des équipes <span class="badge">{{$teams->total()}}</span></h3>
            <div class="box-tools mb40">
              <a href="javascript:void(0)" onclick="return Team.form()" class="btn bg-maroon" title="" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
            </div>
          </div>
          @if(count($teams)>0)
            <div class="box-body table-responsive no-padding mb40">
              <table class="table table-hover table-strped table-inversed-blue">
                <tr>
                  <th>Nom</th>
                  <th>Description</th>
                  <th>Nombre de collaborateurs</th>
                  <th>Créé le</th>
                  <th class="text-center">Actions</th>
                </tr>
                @foreach($teams as $key => $team)
                  <tr>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->description ? str_limit($team->description, 100) : '---' }}</td>
                    <td>{{ $team->users->count() }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($team->created_at)) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a href="javascript:void(0)" onclick="return Team.form({{{$team->id}}})" class=""><i class="fa fa-edit"></i> Modifier</a>
                          </li>
                          <li>
                            <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer équipe ?', 'Etes-vous sur de vouloir supprimer ce questionnaire ?','Team.delete', {tid: {{$team->id}} }, {width: 450})" class=""><i class="fa fa-trash"></i> Supprimer</a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </table>
              {{ $teams->links() }}
            </div>
          @else
            @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection