@extends('layouts.app')
@section('content')
    <section class="content objectifs">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des objectifs</h3>
                        <div class="box-tools mb40">
                            <a onclick="return chmObjectif.create()" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($objectifs)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered table-inversed-blue">
                                <tr>
                                    <th>Titre</th>
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
                                            <input type="text" readonly="">
                                        </td>
                                        <td>
                                            {{ $sub->ponderation }}
                                            <input type="hidden" name="objectifs[ponderation][]" value="{{$sub->ponderation}}">
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </table>
                            {{ $objectifs->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  