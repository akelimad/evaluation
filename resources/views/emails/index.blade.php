@extends('layouts.app')

@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><i class="glyphicon glyphicon-user"></i> La liste des emails <span class="badge">{{$emails->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a onclick="return chmEmail.create()" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($emails)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th>Emetteur</th>
                                    <th>Object</th>
                                    <th>Message</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                @foreach($emails as $key => $email)
                                <tr>
                                    <td> {{ $email->sender }} </td>
                                    <td> {{ $email->subject }} </td>
                                    <td> {{ $email->message }} </td>
                                    <td class="text-center"> 
                                        <a href="javascript:void(0)" onclick="return chmEmail.edit({id: {{$email->id}}})" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer" > <i class="glyphicon glyphicon-pencil"></i> 
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $emails->links() }}
                        </div>
                        <div class="sendInvitationBtn mb40">
                            <a onclick="return chmEntretien.entretiens()" class="btn btn-success"> <i class="fa fa-send"></i> Envoyer une invitation</a>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

