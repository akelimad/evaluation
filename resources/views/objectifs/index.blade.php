@extends('layouts.app')
@section('content')

    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des objectifs </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}">Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/evaluations')}}">Evaluations</a></li>
                            <li><a href="">Carrieres</a></li>
                            <li><a href="">Formations</a></li>
                            <li><a href="">Competences</a></li>
                            <li class="active"><a href="#">Objectifs</a></li>
                            <li><a href="">Salaires</a></li>
                            <li><a href="">Commentaires</a></li>
                        </ul>
                        <div class="tab-content">
                            @if(count($objectifs)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <form action="{{url('objectifs/updateNoteObjectifs')}}">
                                        <table class="table table-hover table-bordered table-inversed-blue">
                                            <tr>
                                                <th>Critères d'évaluation</th>
                                                <th>Note</th>
                                                <th>Pondération % </th>
                                            </tr>
                                            @foreach($objectifs as $objectif)
                                                <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
                                                <tr>
                                                    <td colspan="3" class="objectifTitle"> <b>{{ $objectif->title }}</b> </td>
                                                </tr>
                                                @foreach($objectif->children as $sub)
                                                <tr>
                                                    <td>{{ $sub->title }}</td>
                                                    <td class="criteres">
                                                        <input type="text" id="slider" name="objectifs[{{$objectif->id}}][note][]" data-provide="slider" data-slider-min="1" data-slider-max="100" data-slider-step="1" data-slider-value="{{ isset($sub->note) ? $sub->note : '' }}" data-slider-tooltip="{{isset($sub->note) && $sub->note > 1 ? 'always' : '' }}" required >
                                                        <input type="hidden" name="subObjectifIds[{{$objectif->id}}][]" value="{{$sub->id}}">
                                                    </td>
                                                    <td>
                                                        {{ $sub->ponderation }}
                                                        <input type="hidden" name="objectifs[{{$objectif->id}}][ponderation][]" value="{{$sub->ponderation}}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="sousTotal"> 
                                                        Sous-total  <span class="pull-right">{{$objectif->sousTotal ? $objectif->sousTotal : 0.00}}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3" class="btn-warning">
                                                    TOTAL DE L'ÉVALUATION  <span class="pull-right">
                                                        {{ $total }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="btn-danger">
                                                    NOTE FINALE  <span class="pull-right">0.00 %</span>
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="submit" value="Sauvegarder" class="btn btn-success">
                                    </form>
                                    {{ $objectifs->links() }}
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
  

