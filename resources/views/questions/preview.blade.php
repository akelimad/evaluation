@extends('layouts.app')
@section('content')
    <section class="content showQuestion">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <ul class="list-group">
                                    @foreach($groupes as $g)
                                        <li class="list-group-item">
                                            <h3>{{ $g->name }}</h3>
                                            @foreach($g->questions as $q)
                                            <div class="form-group">
                                                <label for="titre" class="control-label help-block">{{$q->titre}}</label>
                                                @if($q->type == 'text')
                                                <input type="{{$q->type}}" name="titre" id="titre" class="form-control">
                                                @elseif($q->type == 'textarea')
                                                <textarea name="textarea" class="form-control"></textarea>
                                                @endif
                                            </div>
                                            @endforeach
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  