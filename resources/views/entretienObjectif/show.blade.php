
@if(count($objectifs)>0)
    <div class="box-body table-responsive no-padding mb40">
        <table class="table table-hover table-bordered table-inversed-blue">
            <tr>
                <th>Titre</th>
                <th>Pondération % </th>
            </tr>
            @foreach($objectifs as $objectif)
                <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
                <tr>
                    <td colspan="3" class="objectifTitle"> {{ $objectif->title }} </td>
                </tr>
                @foreach($objectif->children as $sub)
                <tr>
                    <td>{{ $sub->title }}</td>
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
    @include('partials.alerts.warning', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
@endif