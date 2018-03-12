
<div class="row">
    @if(count($groupes)>0)
    <div class="col-md-6">
        <h4 class="alert alert-info"> {{ $user->name." ".$user->last_name }} </h4>
        <div class="panel-group">
            @foreach($groupes as $g)
                <div class="panel panel-info">
                    <div class="panel-heading">{{ $g->name }}</div>
                    <div class="panel-body">
                    @forelse($g->questions as $q)
                        <div class="form-group">
                            @if($q->parent == null)
                                <label for="" class="questionTitle help-block text-blue"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-6">
        <h4 class="alert alert-info"> {{ App\User::getMentor($user->id)->name." ".App\User::getMentor($user->id)->last_name }} </h4>
        <form action="{{url('answers/store')}}" method="post">
            <input type="hidden" name="entretien_id" value="{{$e->id}}">
            <input type="hidden" name="mentor_id" value="{{Auth::user()->id}}">
            <input type="hidden" name="user_id" value="{{$user->id}}">
            {{ csrf_field() }}
            <div class="panel-group">
                @foreach($groupes as $g)
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ $g->name }}</div>
                        <div class="panel-body">
                        @forelse($g->questions as $q)
                            <div class="form-group">
                                @if($q->parent == null)
                                    <label for="" class="questionTitle help-block text-blue"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
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
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider vos réponses</button>
        </form>
    </div>
    @else
        <p class="alert alert-default">Aucune donnée disponible !</p>
    @endif
</div>
  