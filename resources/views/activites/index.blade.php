@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> Remplir votre évaluation pour: {{ $e->titre }} </h3>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}" >Synthèse</a></li>
                            <li class="active"><a href="{{url('entretiens/'.$e->id.'/'.$user->id.'/evaluation')}}" > Evaluation </a></li>
                            @foreach($to_fill as $key => $value)
                                @if (in_array($key, json_decode($e->evaluations)))
                                    <li><a href="{{url('entretiens/'.$e->id.'/activites')}}" > {{ $value }} </a></li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            <div class="box-body mb40">
                                @role('MENTOR')
                                    @include('questions/survey2')
                                @endrole
                                @role('COLLABORATEUR')
                                    @include('questions/survey')
                                @endrole
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  