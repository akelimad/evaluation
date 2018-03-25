@extends('layouts.app')
@section('content')

<section class="content objectifs">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary card">
                <h3 class="mb40"> La liste des objectifs pour: {{$e->titre}} </h3>
                <div class="nav-tabs-custom">
                    @include('partials.tabs')
                    <div class="tab-content">
                        @if(count($objectifs)>0)
                            <div class="box-body table-responsive no-padding mb40">
                                <form action="{{url('objectifs/updateNoteObjectifs')}}">
                                    <input type="hidden" name="entretien_id" value="{{$e->id}}">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <table class="table table-hover table-bordered table-inversed-blue">
                                        <tr>
                                            <th>Critères d'évaluation</th>
                                            <th>Note</th>
                                            <th>Apréciation</th>
                                            <th>Pondération(%) </th>
                                            <th>Objectif N+1 </th>
                                        </tr>
                                        @php($c = 0)
                                        @php($total = 0)
                                        @php($sousT = 0)
                                        @foreach($objectifs as $objectif)
                                            @php($c+=1)
                                            <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
                                            <tr>
                                                <td colspan="5" class="objectifTitle"> 
                                                    {{ $objectif->title }} 
                                                </td>
                                            </tr>
                                            @php($sousTotal = 0)
                                            @php($sumPonderation = 0)
                                            @foreach($objectif->children as $sub)

                                                @php( $sumPonderation += $sub->ponderation )
                                                @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                                                    @php( $sousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->note * $sub->ponderation )
                                                @endif
                                                
                                            <tr>
                                                <td>{{ $sub->title }}</td>
                                                <td class="criteres text-center">
                                                    <input type="hidden" name="subObjectifIds[{{$objectif->id}}][]" value="{{$sub->id}}">
                                                    @if(!App\Objectif::getNmoins1Note($sub->id, $e->id) || (App\Objectif::getNmoins1Note($sub->id, $e->id) == true && App\Objectif::getNmoins1Note($sub->id, $e->id)->objNplus1 == 0 ) )
                                                    <input type="text" id="slider" placeholder="Votre note" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" data-provide="slider" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->note : '0' }}" data-slider-tooltip="" >
                                                    <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" value="">
                                                    @else
                                                    <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" value="">
                                                    <table class="table table-bordered table-sub-objectif">
                                                        <tr>
                                                            <td>N-1</td>
                                                            <td>Realisé</td>
                                                            <td>Ecart</td>
                                                            <td>N+1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="nMoins1-{{$sub->id}}" > {{App\Objectif::getNmoins1Note($sub->id, $e->id) ? App\Objectif::getNmoins1Note($sub->id, $e->id)->note : ''}} </span>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" max="10" class="text-center realise" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" data-id="{{$sub->id}}" value="{{App\Objectif::getRealise($sub->id, $e->id) ? App\Objectif::getRealise($sub->id, $e->id)->realise : ''}}">
                                                            </td>
                                                            <td>
                                                                <span class="ecart-{{$sub->id}}"></span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" class="form-control" value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->appreciation : '' }}" placeholder="Pourquoi cette note ?">
                                                </td>
                                                <td class="text-center">
                                                    {{ $sub->ponderation }}
                                                    <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" value="{{$sub->ponderation}}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" {{isset(App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1) && App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1 == 1 ? 'checked':''}}>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5" class="sousTotal"> 
                                                    Sous-total  
                                                    <span class="badge badge-success pull-right">{{number_format($sousTotal/$sumPonderation, 2)}}</span>
                                                </td>
                                            </tr>
                                            @php( $total += number_format($sousTotal/$sumPonderation, 2) )
                                        @endforeach
                                        <tr>
                                            <td colspan="5" class="btn-warning">
                                                TOTAL DE L'ÉVALUATION  
                                                <span class="btn btn-info pull-right">{{ App\Objectif::cutNum($total/$c) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="btn-danger">
                                                NOTE FINALE
                                                <span class="btn btn-info pull-right"> {{ App\Objectif::cutNum($total/$c) *10 }} % </span>
                                            </td>
                                        </tr>
                                    </table>
                                    @if(!App\Objectif::filledObjectifs($e->id, $user->id, $user->parent->id))
                                    <input type="submit" value="Sauvegarder" class="btn btn-success">
                                    @endif
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
  

