@extends('layouts.app')

@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><i class="glyphicon glyphicon-envelope"></i> La liste des emails <span class="badge">{{$emails->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a onclick="return chmEmail.create()" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($emails)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th style="width: 10%">Emetteur</th>
                                    <th style="width: 10%">Nom</th>
                                    <th style="width: 30%">Object</th>
                                    <th style="width: 50%">Message</th>
                                    <th style="width: 10%" class="text-center">Action</th>
                                </tr>
                                @foreach($emails as $key => $email)
                                <tr>
                                    <td> {{ $email->sender }} </td>
                                    <td> {{ $email->name }} </td>
                                    <td> {{ $email->subject }} </td>
                                    <td> {{ str_limit($email->message, 80) }} </td>
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

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><i class="glyphicon glyphicon-envelope"></i> La liste des actions <span class="badge">{{$emailActions->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a onclick="return chmEmailAction.create()" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($emailActions)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th style="width: 10%">Réf action</th>
                                    <th style="width: 40%">Nom</th>
                                    <th style="width: 10%">type</th>
                                    <th style="width: 30%">email template</th>
                                    <th style="width: 10%" class="text-center">Action</th>
                                </tr>
                                @foreach($emailActions as $action)
                                <form action="{{ url('emails/actions/'.$action->id.'/attach') }}" method="post">
                                    {{ csrf_field() }}
                                    <tr>
                                        <td> {{ $action->slug }} </td>
                                        <td> {{ $action->name }} </td>
                                        <td> {{ $action->type == 0 ? 'Manuel':'Automatique' }} </td>
                                        <td>
                                            <select name="email_id" class="form-control">
                                                @foreach($emails as $email)
                                                    <option value="{{$email->id}}" {{ in_array($email->id, $action->emails()->pluck('email_id')->toArray()) ? 'selected':'' }}> {{ $email->subject }} </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center"> 
                                            <a href="javascript:void(0)" onclick="return chmEmailAction.edit({id: {{$action->id}}})" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer" > <i class="glyphicon glyphicon-pencil"></i> 
                                            </a>
                                            <button class="btn-primary icon-fill" data-toggle="tooltip" title="Lier l'action et l'email qui va être envoyé"> <i class="fa fa-save"></i> </button>
                                        </td>
                                    </tr>
                                </form>
                                @endforeach
                            </table>
                            {{ $emailActions->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

