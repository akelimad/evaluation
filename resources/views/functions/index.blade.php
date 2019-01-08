@extends('layouts.app')
@section('title', 'Focntions')
@section('content')
    <section class="content setting">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <ul class="list-group">
                            @foreach(App\Setting::$models as $model)
                                <li class="list-group-item {{ $model['active'] == $active ? 'active':'' }}"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a></li>
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
                                    <th>Id</th>
                                    <th>Titre</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                                @foreach($results as $key => $f)
                                    <tr>
                                        <td> {{ $f->id }}</td>
                                        <td> {{ $f->title }}</td>
                                        <td class="text-right">
                                            <a href="javascript:void(0)" onclick="return Fonction.form({{$f->id}})" class="btn-primary icon-fill" title="Modifier" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                            <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer la fonction ?', 'Etes-vous sur de vouloir supprimer ?','Fonction.delete', {id: {{$f->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer"> <i class="fa fa-trash"></i> </a>
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
