
<div class="row">
    <div class="col-md-12 survey">
        @if(count($groupes)>0)
        <form action="{{url('answers/store')}}" method="post" class="surveyForm">
            <input type="hidden" name="entretien_id" value="{{$e->id}}">
            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
            <input type="hidden" name="mentor_id" value="NULL">
            {{ csrf_field() }}
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
                                <input type="{{$q->type}}" name="answers[{{$q->id}}][]" class="form-control" value="{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}" required="">
                                @elseif($q->type == 'textarea')
                                <textarea name="answers[{{$q->id}}][]" class="form-control" required="">{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}</textarea>
                                @elseif($q->type == "checkbox")
                                    <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez cocher au moins un élement</p>
                                    @foreach($q->children as $child)
                                        <div class="survey-checkbox">
                                            <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->titre}}" value="{{$child->id}}" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && in_array($child->id, App\Answer::getCollAnswers($q->id, $user->id, $e->id)) ? 'checked' : '' }}>
                                            <label for="{{$child->titre}}">{{ $child->titre }}</label>
                                        </div>
                                    @endforeach
                                    <div class="clearfix"></div>
                                @elseif($q->type == "radio")
                                    @foreach($q->children as $child)
                                        <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->id}}" value="{{$child->id}}" required="" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && in_array($child->id, App\Answer::getCollAnswers($q->id, $user->id, $e->id)) ? 'checked' : '' }}> 
                                        <label for="{{$child->id}}">{{ $child->titre }}</label>
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
            <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
            @if(App\Entretien::answered($e->id, Auth::user()->id) == false)
            <button type="submit" class="btn btn-success" id="submitAnswers"><i class="fa fa-check"></i> Valider vos réponses</button>
            @endif
        </form>
        @else
            <p class="alert alert-default">Aucune donnée disponible !</p>
        @endif
    </div>
</div>

  