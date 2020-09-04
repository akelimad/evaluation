@extends('layouts.app')
@section('title', 'Fonctions')
@section('breadcrumb')
  <li>Paramètres</li>
  <li>Fonctions</li>
@endsection
@section('content')
  <section class="content setting">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body">
            <ul class="list-group">
              @foreach(App\Setting::$models as $model)
                <li class="list-group-item {{ $model['active'] == $active ? 'active':'' }}"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des fonctions <span class="badge">{{$results->total()}}</span></h3>

            <div class="box-tools mb40">
              <a href="javascript:void(0)" onclick="return Fonction.form({})" class="btn bg-maroon" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
            </div>
          </div>
          @if(count($results)>0)
            <div class="box-body table-responsive no-padding mb40">
              <table class="table table-hover table-striped table-inversed-blue">
                <tr>
                  <th>Titre</th>
                  <th class="text-right">Actions</th>
                </tr>
                @foreach($results as $key => $f)
                  <tr>
                    <td>{{ $f->title }}</td>
                    <td class="text-right">
                      {{ csrf_field() }}
                      <div class="btn-group dropdown">
                        <button aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a href="javascript:void(0)" onclick="return Fonction.form({{{$f->id}}})" class=""> <i class="fa fa-edit"></i> Modifier</a>
                          </li>
                          <li>
                            <a href="javascript:void(0)" class="" onclick="return chmModal.confirm('', 'Supprimer la fonction', 'Etes-vous sur de vouloir supprimer ?','Fonction.delete', {id: {{$f->id}} }, {width: 450})"> <i class="fa fa-trash"></i> Supprimer</a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </table>
              {{ $results->links() }}
            </div>
          @else
            @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
