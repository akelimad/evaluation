@extends('layouts.app')
@section('content')
    <section class="content showQuestion">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <h3 class="mb40"> Validation des réponses du collaborateur par le mentor ! </h3>
                        <div class="row">
                            <div class="col-md-6">
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
                                                    <input type="{{$q->type}}" class="form-control" readonly="" value="{{App\Answer::getAnswers($q->id)->answer}}">
                                                @elseif($q->type == 'textarea')
                                                    <textarea class="form-control" readonly="">{{App\Answer::getAnswers($q->id)->answer}}</textarea>
                                                @elseif($q->type == "checkbox")
                                                    @foreach($q->children as $child)
                                                        <div class="survey-checkbox">
                                                            <input type="{{$q->type}}" value="{{$child->id}}" {{ in_array($child->id, App\Answer::getAnswers($q->id)) ? 'checked' : '' }} disabled>
                                                            <label >{{ $child->titre }}</label>
                                                        </div>
                                                    @endforeach
                                                    <div class="clearfix"></div>
                                                @elseif($q->type == "radio")
                                                    @foreach($q->children as $child)
                                                        <input type="{{$q->type}}" value="{{$child->id}}" {{ in_array($child->id, App\Answer::getAnswers($q->id)) ? 'checked' : '' }} disabled> 
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
                                <form action="{{url('answers/store')}}" method="post">
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
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  