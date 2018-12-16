@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('mentor_comment'))
                    @include('partials.alerts.success', ['messages' => Session::get('mentor_comment') ])
                @endif
                <div class="box box-primary card">
                    <h3 class="mb40"> Liste des commentaires pour: {{ $e->titre }} - {{ $user->name." ".$user->last_name }} </h3>
                    <div class="nav-tabs-custom">
                        @include('partials.tabs')
                        <div class="tab-content">
                            @if(count($comments)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <table class="table table-hover table-striped text-center">
                                        <thead>
                                            <tr>  
                                                <th style="width: 10%">Date</th>
                                                <th style="width: 35%">Collaborateur</th>
                                                <th style="width: 35%">Mentor</th>
                                                <th style="width: 10%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comments as $c)
                                            <form action="{{ url('entretiens/'.$e->id.'/u/'.$user->id.'/commentaires/'.$c->id.'/mentorUpdate') }}" method="post">
                                                <input type="hidden" name="mentor_id" value="{{$user->parent->id}}">
                                            {{ csrf_field() }}
                                            {{ method_field('PUT') }}
                                            <tr>
                                                <td> {{ Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i' )}} </td>
                                                <td> {{ $c->userComment }} </td>
                                                <td>
                                                    @if($c->mentorComment)
                                                        {{ $c->mentorComment }}
                                                    @else
                                                        @if($user->id == Auth::user()->id)
                                                        ---
                                                        @else
                                                        <textarea name="mentorComment" class="form-control" style="min-height: 0;height: 40px" required="" maxlength="350"></textarea> 
                                                        @endif
                                                    @endif
                                                </td>
                                                <td> 
                                                    @if($user->id == Auth::user()->id)
                                                    <a href="javascript:void(0)" onclick="return chmComment.edit({eid: {{$e->id}}, uid: {{$user->id}}, cid: {{$c->id}} })" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer votre commentaire"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                    @else
                                                    <button type="submit" class="btn-info icon-fill" data-toggle="tooltip" title="Repondez sur le commentaire de votre collaborateur"><i class="fa fa-paper-plane"></i> </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            </form>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                            @endif
                            <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
                            @if($user->id == Auth::user()->id && count($comments) < 1)
                            <a onclick="return chmComment.create({eid: {{$e->id}}, uid:{{$user->id}} })" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter un commentaire</a>
                            @endif
                        </div>
                    </div>
                    <div class="callout callout-info">
                        <p class="">
                            <i class="fa fa-info-circle fa-2x"></i> 
                            <span class="content-callout">Cette page affiche Liste des commentaires de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  