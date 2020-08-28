@extends('layouts.app')
@section('title', 'Fiches métiers')
@section('breadcrumb')
  <li>Fiches métiers</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des fiches métiers <span class="badge"></span></h3>

            <div class="box-tools mb40">
              <a href="javascript:void(0)" onclick="return chmSkill.create()" class="btn bg-maroon" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
            </div>
          </div>
          @if( $count > 0 )
            <div class="box-body table-responsive no-padding mb40">
              <table class="table table-hover table-striped table-inversed-blue">
                <tr>
                  <th>Fonction</th>
                  <th>Titre</th>
                  <th>Description</th>
                  <th>Créée le</th>
                  <th>Modifiée le</th>
                  <th class="text-center">Actions</th>
                </tr>
                @foreach($skills as $key => $skill)
                  <tr>
                    <td>{{ $skill->function_id > 0 ? App\Fonction::find($skill->function_id)->title : '---' }}</td>
                    <td>{{ $skill->title }}</td>
                    <td>{{ str_limit($skill->description, 30) }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($skill->created_at)) }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($skill->updated_at)) }}</td>
                    <td class="text-center">
                      <div class="btn-group dropdown">
                        <button aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a href="javascript:void(0)" onclick="return chmSkill.edit({id: {{$skill->id}}})" class=""> <i class="fa fa-edit"></i> Modifier</a>
                          </li>
                          <li>
                            <a href="javascript:void(0)" class="" onclick="return chmModal.confirm('', 'Supprimer la fiche métier', 'Etes-vous sur de vouloir supprimer ?','chmSkill.delete', {id: {{$skill->id}} }, {width: 450})"> <i class="fa fa-trash"></i> Supprimer</a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </table>
            </div>
          @else
            @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection