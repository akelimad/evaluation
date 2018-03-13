@extends('layouts.app')
@section('content')
    <section class="content objectifs">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des objectifs</h3>
                        <div class="box-tools mb40">
                            <a onclick="return chmObjectif.create()" class="btn bg-maroon"> <i class="fa fa-user-plus"></i> Ajouter </a>
                        </div>
                    </div>
                    @if(count($objectifs)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-bordered table-inversed-blue">
                                <tr>
                                    <th>Titre</th>
                                    <th>Note</th>
                                    <th>Pondération</th>
                                </tr>
                                @foreach($objectifs as $objectif)
                                    <tr>
                                        <td colspan="3" class="text-center"> <b>{{ $objectif->title }}</b> </td>
                                    </tr>
                                    @foreach($objectif->children as $sub)
                                    <tr>
                                        <td>{{ $sub->title }}</td>
                                        <td>{{ $sub->note }}</td>
                                        <td>{{ $sub->ponderation }}</td>
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
  