@extends('layouts.app')
@section('title', 'Questions')
@section('breadcrumb')
    <li>Questionnaires</li>
    <li>{{ $qs->groupe->survey->title }}</li>
    <li>Groupes</li>
    <li title="{{ $qs->groupe->name }}">{{str_limit($qs->groupe->name,20)}}</li>
    <li>Questions</li>
@endsection
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
                    <div class="box-body groupeQuestions">
                        <p class="">
                            <a href="javascript:void(0)" onclick="return chmSurvey.show({id: {{$sid}} })"> <i class="fa fa-eye"></i> preview </a> |
                            <a href="{{ url('surveys/'.$sid.'/groupes') }}" > <i class="fa fa-list"></i> Types </a> | 
                            <a href="{{ url('surveys') }}"> <i class="fa fa-list"></i> Questionnaires </a> 
                        </p>
                        <div class="accordion" id="accordion2">
                            @foreach($groupes as $g)
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse{{$g->id}}" {{ $g->id == $gr->id ? 'aria-expanded="true"': '' }}>
                                    {{ $g->name }}
                                    </a>
                                    <a href="javascript:void(0)" onclick="return chmQuestion.create({sid: {{$sid}} ,gid: {{$g->id}} })" class="icon-add-question" data-toggle="tooltip" title="Ajouter une question pour ce groupe"> <i class="fa fa-plus btn-success circle-icon"></i> </a>
                                </div>
                                <div id="collapse{{$g->id}}" class="accordion-body collapse {{ $g->id == $gr->id ? 'in': '' }}">
                                    <div class="accordion-inner">
                                        
                                        @if(count($g->questions)>0)
                                            <ul class="list-group">
                                                @foreach($g->questions as $q)
                                                    @if($q->parent_id == 0)
                                                    {{ csrf_field() }}
                                                    <li class="list-group-item">
                                                        <a href="{{url('surveys/'.$sid.'/groupes/'.$g->id.'/questions/'.$q->id)}}">{{ str_limit($q->titre, 20) }}</a>
                                                        <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer la question ?', 'Etes-vous sur de vouloir supprimer cette question ?','chmQuestion.delete', {sid: {{$sid}} ,gid: {{$g->id}}, qid:{{$q->id}} }, {width: 450})" class="text-red circle-icon pull-right" data-toggle="tooltip" title="Supprimer"> <i class="fa fa-trash"></i> </a>
                                                        <a href="javascript:void(0)" onclick="return chmQuestion.edit({ sid: {{$sid}}, gid: {{$g->id}}, qid:{{$q->id}} })" class="text-yellow circle-icon pull-right" data-toggle="tooltip" title="Editer"> <i class="glyphicon glyphicon-pencil"></i> </a> 
                                                        <span class="clearfix"></span>
                                                    </li>
                                                    @endif
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
                            <label class="col-md-3">Questionnaire</label> 
                            <span class="col-md-7"> {{ $qs->groupe->survey->title }} </span>
                            <span class="clearfix"></span>
                        </p>
                        <p>
                            <label class="col-md-3">Types</label> 
                            <span class="col-md-7"> {{ $qs->groupe->name }} </span>
                            <span class="clearfix"></span>
                        </p>
                        <p>
                            <label class="col-md-3">Question</label> 
                            <span class="col-md-7"> {{ $qs->titre }} </span>
                            <span class="clearfix"></span>
                        </p>
                        @if(($qs->type == "checkbox" || $qs->type == "radio") && count($qs->children)>0 )
                        <p>
                            <label class="col-md-3">Choix</label>
                            <div class="col-md-7">
                                @foreach($qs->children as $choice) 
                                    <input type="{{$choice->parent->type}}" disabled=""> {{ $choice->titre }}
                                @endforeach
                            </div>
                                <span class="clearfix"></span>
                        </p>
                        @endif
                        <div class="">
                            <div class="col-md-4">
                                <div class="box box-success box-solid">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">Ajouter une question au groupe</h4>
                                    </div>
                                    <a href="javascript:void(0)" onclick="return chmQuestion.create({sid: {{$sid}}, gid: {{$q->groupe->id}} })">
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
  