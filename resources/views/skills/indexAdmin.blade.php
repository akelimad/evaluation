@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des compétences <span class="badge">{{$skills->total()}}</span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmSkill.create()" class="btn bg-maroon" title="Ajouter une compétence" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($skills)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered table-inversed-blue">
                                <tr>
                                    <th>Id</th>
                                    <th>Axe</th>
                                    <th>Famille</th>
                                    <th>Catégorie</th>
                                    <th>Compétence</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                @foreach($skills as $key => $skill)
                                <tr>
                                    <td> {{ $key+1 }}</td>
                                    <td> {{ $skill->axe ? $skill->axe : '---' }}</td>
                                    <td> {{ $skill->famille ? $skill->famille : '---' }} </td>
                                    <td> {{ $skill->categorie ? $skill->categorie : '---' }} </td>
                                    <td> {{ $skill->competence ? $skill->competence : '---' }} </td>
                                    <td class="text-center">  
                                        <a href="javascript:void(0)" onclick="return chmSkill.edit({id: {{$skill->id}}})" class="btn-warning icon-fill" title="Modifier cette compétences" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $skills->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  