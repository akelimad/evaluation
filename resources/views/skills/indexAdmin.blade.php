@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Liste des compétences entretiens <span class="badge"></span></h3>
                        <div class="box-tools mb40">
                            <a href="javascript:void(0)" onclick="return chmSkill.create()" class="btn bg-maroon" title="Ajouter une compétence" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if( $count > 0 )
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped table-inversed-blue">
                                <tr>
                                    <th>Entretien</th>
                                    <th>Axe</th>
                                    <th>Famille</th>
                                    <th>Catégorie</th>
                                    <th>Compétence</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                @foreach($interviewSkills as $key => $row)
                                    @if(App\Entretien::find($row->id)->skills->count()>0)
                                    @php($entretienSkills = App\Entretien::find($row->id)->skills)
                                        <tr>
                                            {{ csrf_field() }}
                                            <td colspan="5"> {{ $row->titre }} </td>
                                            <td class="text-center">  
                                                <a href="javascript:void(0)" onclick="return chmSkill.edit({id: {{$row->id}}})" class="btn-warning icon-fill" title="Modifier les compétences de cette entretien" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer les compétences ?', 'Etes-vous sur de vouloir supprimer les compétences de cette entretien ?','chmSkill.delete', {eid: {{$row->id}} }, {width: 450})" class="btn-danger icon-fill" data-toggle="tooltip" title="Supprimer les compétences de cette entretien"> <i class="fa fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                        @foreach($entretienSkills as $key => $skill )
                                        <tr>
                                            <td>  </td>
                                            <td> {{ $skill->axe ? $skill->axe : '---' }}</td>
                                            <td> {{ $skill->famille ? $skill->famille : '---' }} </td>
                                            <td> {{ $skill->categorie ? $skill->categorie : '---' }} </td>
                                            <td> {{ $skill->competence ? $skill->competence : '---' }} </td>
                                            <td> </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  