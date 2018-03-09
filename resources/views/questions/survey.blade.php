
<div class="row">
    <div class="col-md-12">
        <form action="{{url('answers/store')}}" method="post">
            <input type="hidden" name="entretien_id" value="{{$e->id}}">
            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
            {{ csrf_field() }}
            <ul class="list-group">
                @foreach($groupes as $g)
                    <li class="list-group-item">
                        <h3 class="mb40">{{ $g->name }}</h3>
                        @forelse($g->questions as $q)
                        <div class="form-group">
                            @if($q->parent == null)
                            <p class="control-label help-block">{{$q->titre}}</p>
                            @endif
                            @if($q->type == 'text')
                            <input type="{{$q->type}}" name="answers[{{$q->id}}][]" class="form-control" value="{{App\Answer::getAnswers($q->id, $user->id) ? App\Answer::getAnswers($q->id, $user->id)->answer : '' }}">
                            @elseif($q->type == 'textarea')
                            <textarea name="answers[{{$q->id}}][]" class="form-control" >{{App\Answer::getAnswers($q->id, $user->id) ? App\Answer::getAnswers($q->id, $user->id)->answer : '' }}</textarea>
                            @elseif($q->type == "checkbox")
                                @foreach($q->children as $child)
                                    <div class="survey-checkbox">
                                        <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->titre}}" value="{{$child->id}}" {{ in_array($child->id, App\Answer::getAnswers($q->id, $user->id)) ? 'checked' : '' }}>
                                        <label for="{{$child->titre}}">{{ $child->titre }}</label>
                                    </div>
                                @endforeach
                                <div class="clearfix"></div>
                            @elseif($q->type == "radio")
                                @foreach($q->children as $child)
                                    <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->id}}" value="{{$child->id}}" {{ in_array($child->id, App\Answer::getAnswers($q->id, $user->id)) ? 'checked' : '' }}> 
                                    <label for="{{$child->id}}">{{ $child->titre }}</label>
                                @endforeach
                            @endif
                        </div>
                        @empty
                            <p class="help-block"> Aucune question </p>
                        @endforelse
                    </li>
                @endforeach
            </ul>
            <input type="submit" class="btn btn-success" value="Valider vos réponses">
        </form>
    </div>
</div>

  