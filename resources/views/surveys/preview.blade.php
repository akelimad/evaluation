@if(count($groupes)>0)
<div class="panel-group">
    @foreach($groupes as $g)
        @if(count($g->questions)>0)
        <div class="panel panel-info">
            <div class="panel-heading">{{ $g->name }}</div>
            <div class="panel-body">
            @forelse($g->questions as $q)
                <div class="form-group">
                    @if($q->parent == null)
                        <label for="" class="questionTitle help-block text-blue"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
                    @endif
                    @if($q->type == 'text')
                        <input type="{{$q->type}}" class="form-control" readonly="">
                    @elseif($q->type == 'textarea')
                        <textarea class="form-control" readonly=""></textarea>
                    @elseif($q->type == "checkbox")
                        @foreach($q->children as $child)
                            <div class="survey-checkbox">
                                <input type="{{$q->type}}">
                                <label >{{ $child->titre }}</label>
                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                    @elseif($q->type == "radio")
                        @foreach($q->children as $child)
                            <input type="{{$q->type}}"> 
                            <label >{{ $child->titre }}</label>
                        @endforeach
                    @endif
                </div>
            @empty
                <p class="help-block"> Aucune question </p>
            @endforelse
            </div>
        </div>
        @endif
    @endforeach
</div>
@else
    @include('partials.alerts.warning', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
@endif
