
<div class="row">
    <div class="col-md-6">
        <h4 class="alert alert-info"> {{ $user->name." ".$user->last_name }} </h4>
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
                            <input type="{{$q->type}}" class="form-control" readonly="" value="{{App\Answer::getAnswers($q->id, $user->id) != null ? App\Answer::getAnswers($q->id, $user->id)->answer : ''}}">
                        @elseif($q->type == 'textarea')
                            <textarea class="form-control" readonly="">{{App\Answer::getAnswers($q->id, $user->id) != null ? App\Answer::getAnswers($q->id, $user->id)->answer :''}}</textarea>
                        @elseif($q->type == "checkbox")
                            @foreach($q->children as $child)
                                <div class="survey-checkbox">
                                    <input type="{{$q->type}}" value="{{$child->id}}" {{ in_array($child->id, App\Answer::getAnswers($q->id, $user->id)) ? 'checked' : '' }} disabled>
                                    <label >{{ $child->titre }}</label>
                                </div>
                            @endforeach
                            <div class="clearfix"></div>
                        @elseif($q->type == "radio")
                            @foreach($q->children as $child)
                                <input type="{{$q->type}}" value="{{$child->id}}" {{ in_array($child->id, App\Answer::getAnswers($q->id, $user->id)) ? 'checked' : '' }} disabled> 
                                <label >{{ $child->titre }}</label>
                            @endforeach
                        @endif
                    </div>
                    @empty
                        <p class="help-block"> Aucune question </p>
                    @endforelse
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-6">
        <h4 class="alert alert-info"> {{ App\User::getMentor($user->id)->name." ".App\User::getMentor($user->id)->last_name }} </h4>
        <form action="{{url('answers/store')}}" method="post">
            <input type="hidden" name="entretien_id" value="{{$e->id}}">
            <input type="hidden" name="mentor_id" value="{{Auth::user()->id}}">
            <input type="hidden" name="user_id" value="{{$user->id}}">
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
                            <input type="{{$q->type}}" name="answers[{{$q->id}}][]" class="form-control">
                            @elseif($q->type == 'textarea')
                            <textarea name="answers[{{$q->id}}][]" class="form-control" ></textarea>
                            @elseif($q->type == "checkbox")
                                @foreach($q->children as $child)
                                    <div class="survey-checkbox">
                                        <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->titre}}" value="{{$child->id}}">
                                        <label for="{{$child->titre}}">{{ $child->titre }}</label>
                                    </div>
                                @endforeach
                                <div class="clearfix"></div>
                            @elseif($q->type == "radio")
                                @foreach($q->children as $child)
                                    <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->id}}" value="{{$child->id}}"> 
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
  