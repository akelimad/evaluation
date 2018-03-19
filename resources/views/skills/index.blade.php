@extends('layouts.app')
@section('content')

    <section class="content skills">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des compétences </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}">Synthèse</a></li>
                            @foreach($evaluations as $evaluation)
                            <li class="{{ Request::segment(5) == $evaluation->title ? 'active':'' }}">
                                <a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/'.$evaluation->title)}}">{{ $evaluation->title }}</a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @if(count($skills)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <form action="">
                                        <table class="table table-hover table-bordered table-inversed-blue text-center">
                                            <tr>
                                                <th>Axe</th>
                                                <th>Famille</th>
                                                <th>Catégorie</th>
                                                <th>Compétence</th>
                                                <th>Objectif</th>
                                                <th>Auto</th>
                                                <th>N+1</th>
                                                <th>Ecart</th>
                                            </tr>
                                            @foreach($skills as $skill)
                                            <tr>
                                                <td> {{ $skill->axe ? $skill->axe : '---' }}</td>
                                                <td> {{ $skill->famille ? $skill->famille : '---' }} </td>
                                                <td> {{ $skill->categorie ? $skill->categorie : '---' }} </td>
                                                <td> {{ $skill->competence ? $skill->competence : '---' }} </td>
                                                <td>
                                                    <input type="number" min="0" max="10">
                                                </td><td>
                                                    <input type="number" min="0" max="10">
                                                </td><td>
                                                    <input type="number" min="0" max="10">
                                                </td><td>
                                                    <input type="number" min="0" max="10">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        <button type="submit" class="btn btn-success pull-right"> <i class="fa fa-check"></i> Sauvegarder</button>
                                    </form>
                                    {{ $skills->links() }}
                                </div>
                            @else
                                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  

