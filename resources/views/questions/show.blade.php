@extends('layouts.app')
@section('content')
    <section class="content showQuestion">
        @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if(Session::has($key))
                <div class="chm-alerts alert alert-info alert-white rounded">
                    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
                    <div class="icon"><i class="fa fa-info-circle"></i></div>
                    <span> {!! Session::get($key) !!} </span>
                </div>
            @endif
        @endforeach
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <p class="lead"> <a href="{{ url('surveys/'.$sid.'/preview') }}"> <i class="fa fa-eye"></i> preview </a> </p>
                        <div class="accordion" id="accordion2">
                            @foreach($groupes as $g)
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse{{$g->id}}" {{ $g->id == $gr->id ? 'aria-expanded="true"': '' }}>
                                    {{ $g->name }}
                                    </a>
                                    <a href="javascript:void(0)" onclick="return chmQuestion.create({gid: {{$g->id}} })" class="icon-add-question" data-toggle="tooltip" title="Ajouter une question pour ce groupe"> <i class="fa fa-plus circle-icon"></i> </a>
                                </div>
                                <div id="collapse{{$g->id}}" class="accordion-body collapse {{ $g->id == $gr->id ? 'in': '' }}">
                                    <div class="accordion-inner">
                                        
                                        @if(count($g->questions)>0)
                                            <ul class="list-group">
                                                @foreach($g->questions as $q)
                                                    <li class="list-group-item">
                                                        <a href="{{url('surveys/'.$sid.'/groupes/'.$g->id.'/questions/'.$q->id)}}">{{ $q->titre }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="help-block"> Aucune question </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-body">
                        <h3 class="title mb40"> Resumé de la question </h3>
                        <p>
                            <label class="col-md-3">Question groupe</label> 
                            <span class="col-md-7"> {{ $qs->groupe->name }} </span>
                            <span class="clearfix"></span>
                        </p>
                        <p>
                            <label class="col-md-3">Question</label> 
                            <span class="col-md-7"> {{ $qs->titre }} </span>
                            <span class="clearfix"></span>
                        </p>
                        @if($qs->type == "checkbox" || $qs->type == "radio")
                        <p>
                            <label class="col-md-3">Sous question</label> 
                            <span class="col-md-7"> <a href="javascript:void(0)" onclick="return chmQuestion.create({gid: {{$qs->groupe->id}}, parent_id: {{$qs->id}} })">N'oubliez pas d'ajouter les sous questions(ou bien les choix)</a> </span>
                            <span class="clearfix"></span>
                        </p>
                        @endif
                        <div class="">
                            <div class="col-md-4">
                                <div class="box box-success box-solid">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">Ajouter une question au groupe</h4>
                                    </div>
                                    <a href="javascript:void(0)" onclick="return chmQuestion.create({gid: {{$q->groupe->id}} })">
                                        <div class="box-body text-center">
                                            <i class="fa fa-plus fa-5x"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  