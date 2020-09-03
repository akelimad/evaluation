@extends('layouts.app')
@section('title', 'Courriels')
@section('breadcrumb')
  <li>Courriels</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="glyphicon glyphicon-envelope"></i> Liste des emails <span class="badge">{{$emails->total()}}</span></h3>

            <div class="box-tools mb40">
              <a onclick="return chmEmail.form({})" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
            </div>
          </div>
          @if(count($emails)>0)
            <div class="box-body table-responsive no-padding mb40">
              <table class="table table-hover table-striped table-inversed-blue table-slim">
                <tr>
                  <th>Emetteur</th>
                  <th>Nom</th>
                  <th>Object</th>
                  <th class="text-center">Actions</th>
                </tr>
                @foreach($emails as $key => $email)
                  <tr>
                    <td>{{ $email->sender }}</td>
                    <td>{{ $email->name }}</td>
                    <td>{{ str_limit($email->subject, 80) }}</td>
                    <td class="text-center">
                      {{ csrf_field() }}
                      <div class="btn-group dropdown">
                        <button aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-bars"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li>
                            <a href="javascript:void(0)" onclick="return chmEmail.form({{{$email->id}}})" class=""> <i class="fa fa-edit"></i> Modifier</a>
                          </li>
                          <li>
                            <a href="javascript:void(0)" class="" onclick="return chmModal.confirm('', 'Supprimer le template ?', 'Etes-vous sur de vouloir supprimer ce template ?','chmEmail.delete', {tid:{{$email->id}}}, {width: 450})"> <i class="fa fa-trash"></i> Supprimer</a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </table>
              {{ $emails->links() }}
            </div>
            <div class="sendInvitationBtn mb40">
              <a onclick="return chmEntretien.entretiens()" class="btn btn-success"> <i class="fa fa-send"></i> Envoyer
                une invitation</a>
            </div>
          @else
            @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection

