@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des questions groupe <span class="badge">{{$groupes->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmGroupe.create()" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($groupes)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th>Id</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                @foreach($groupes as $key => $g)
                                <tr>
                                    <td> {{ $key+1 }} </td>
                                    <td> {{ $g->name }} </td>
                                    <td> {{ $g->description }} </td>
                                    <td class="text-center">  
                                        <a href="javascript:void(0)" onclick="return chmGroupe.edit({id: {{$g->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>

                                        <a href="javascript:void(0)" onclick="return chmQuestion.create({id: {{$g->id}} })" class="btn-info icon-fill" data-toggle="tooltip" title="Ajouter les questions groupe"> <i class="fa fa-question"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $groupes->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  